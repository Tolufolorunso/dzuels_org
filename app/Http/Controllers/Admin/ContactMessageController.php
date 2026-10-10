<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ContactMessageController extends Controller
{
    /**
     * Display a listing of submitted contact messages.
     */
    public function index(Request $request): View
    {
        $status = $request->query('status', 'all');
        $search = $request->query('q');

        $query = ContactMessage::latest();

        if ($status !== 'all' && in_array($status, ['unread', 'read', 'replied'])) {
            $query->where('status', $status);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('subject', 'like', "%{$search}%")
                    ->orWhere('message', 'like', "%{$search}%");
            });
        }

        $messages = $query->paginate(15)->withQueryString();
        $unreadCount = ContactMessage::unread()->count();
        $totalCount = ContactMessage::count();

        return view('admin.messages.index', compact(
            'messages',
            'status',
            'search',
            'unreadCount',
            'totalCount'
        ));
    }

    /**
     * Update the review status of a contact message.
     */
    public function updateStatus(Request $request, ContactMessage $message): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'string', 'in:unread,read,replied'],
        ]);

        $message->update(['status' => $validated['status']]);

        return back()->with('success', "Status updated to {$validated['status']} for message from {$message->full_name}.");
    }

    /**
     * Delete the specified contact message.
     */
    public function destroy(ContactMessage $message): RedirectResponse
    {
        $sender = $message->full_name;
        $message->delete();

        return back()->with('success', "Message from {$sender} deleted successfully.");
    }
}
