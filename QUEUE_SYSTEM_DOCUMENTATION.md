# Queue System Implementation for Talk to Agent Feature

## Overview
Implemented a complete queue management system for the "Talk to Agent" feature in the floating chatbot, allowing users to see their queue position and admins to manage conversations one at a time.

## Features Implemented

### User Side (Public Floating Chatbot)
1. **Queue Entry**
   - When a user clicks "Talk to Agent", they join a queue
   - Shows queue position: "You are #X in line"
   - Real-time queue position updates every 3 seconds
   - Visual indicator showing current position

2. **Queue Status Display**
   - **Waiting**: Shows yellow badge with queue position
   - **Active**: Shows green badge when connected to agent
   - **Completed**: Shows blue "Conversation Ended" notification
   - Automatic notification when agent becomes available
   - Automatic notification when admin ends conversation

3. **Message Blocking**
   - Users cannot send messages while in queue (status: waiting)
   - Shows warning: "Please wait for your turn. You are #X in line"
   - Messages enabled only when conversation becomes active
   - Messages blocked after conversation is completed
   - Shows message: "This conversation has ended"

### Admin Side (Admin Floating Chatbot)
1. **Queue Dashboard**
   - Shows count of waiting users: "X Users Waiting"
   - "Accept Next User" button when no active conversation
   - Visual display of active conversation with queue count

2. **Active Conversation Management**
   - Only ONE active conversation per admin at a time
   - Shows current user info and conversation history
   - "Complete & Next" button in chat header
   - Automatic transition to next user in queue

3. **Workflow**
   - Admin clicks "Accept Next User" → Conversation activates
   - Chat with user until issue is resolved
   - Click "Complete & Next" → Current conversation completes, next user loads automatically
   - If no users in queue → Returns to inbox view

## Database Structure

### agent_conversations Table Fields
- `queue_position` (integer, nullable) - Position in queue
- `queue_status` (enum) - 'waiting', 'active', 'completed', 'cancelled'
- `queued_at` (timestamp) - When user joined queue
- `assigned_at` (timestamp) - When conversation became active
- `assigned_admin_id` (bigint) - Admin handling the conversation
- `conversation_completed_at` (timestamp) - When conversation ended
- `priority` (string) - Priority level (default: 'normal')
- `estimated_wait_minutes` (integer) - Estimated wait time

### Indexes Added
- `(queue_status, queue_position)` - Fast queue queries
- `(assigned_admin_id, queue_status)` - Admin's active conversations
- `(queued_at, queue_status)` - Time-based queue sorting

## API Endpoints

### User Endpoints
```
POST /api/agent-conversation/escalate
- Joins user to queue
- Returns: session_id, queue_position, queue_status

POST /api/agent-conversation/send-user
- Sends message from user to agent
- Blocks if queue_status is 'waiting'

GET /api/agent-conversation/{sessionId}/queue-status
- Gets current queue position and status
- Polls every 3 seconds

GET /api/agent-conversation/{sessionId}/new-messages
- Gets new messages from agent
```

### Admin Endpoints
```
GET /api/admin/agent-conversation/active
- Gets admin's current active conversation
- Returns queue count

POST /api/admin/agent-conversation/accept-next
- Activates next user in queue
- Checks if admin already has active conversation

POST /api/admin/agent-conversation/complete-and-next
- Completes current conversation
- Automatically activates next user
- Returns has_next flag

POST /api/admin/agent-conversation/send
- Sends message from admin to user

GET /api/admin/agent-conversation/{sessionId}/messages
- Gets conversation history
```

## Model Methods (AgentConversation)

### Scopes
- `inQueue()` - Get waiting users ordered by position
- `activeConversation()` - Get active conversations
- `assignedTo($adminId)` - Get conversations for specific admin

### Static Methods
- `getNextQueuePosition()` - Calculates next available queue position
- `getQueuePosition($sessionId)` - Gets position in queue for a session
- `getNextInQueue()` - Gets next waiting user

### Instance Methods
- `activateConversation($adminId)` - Activates conversation for admin
- `completeConversation()` - Marks conversation as completed

## Frontend Implementation

### User Chatbot (chatbot.js)
```javascript
// New properties
this.queuePosition = null;
this.queueStatus = 'waiting';

// Methods
escalateToAgent() - Shows queue position on join
checkQueueStatus() - Polls queue status every 3s
sendMessageToAgent() - Blocks if still in queue
```

### Admin Chatbot (admin-chatbot.js)
```javascript
// Methods
displayActiveConversation() - Shows current active user
displayQueueOption() - Shows queue count and accept button
acceptNextUser() - Accepts next user from queue
completeAndNext() - Completes and moves to next
```

## UI Components

### User Side
1. **Queue Waiting Badge** (Yellow)
   - Shows "⏳ You're in the Queue"
   - Real-time position number
   - Updates automatically

2. **Active Connection Badge** (Green)
   - Shows "✅ Connected to Agent"
   - Enables messaging

3. **Header Update**
   - Changes to "💬 Talking to Agent" when active
   - Resets to "💬 Barangay Chatbot" when conversation ends

### Conversation End Notification
1. **End Notification** (Blue)
   - Shows "✓ Conversation Ended"
   - Displays thank you message
   - Suggests starting new conversation if needed
   - Automatically stops polling
   - Disables message input

### Admin Side
1. **Inbox View**
   - Shows "X Users Waiting" with count
   - Green "Accept Next User" button
   - Empty state: "No Users Waiting"

2. **Active Conversation View**
   - Blue header showing "Active Conversation"
   - Queue count badge
   - Green "Complete & Next" button

3. **Chat Header**
   - User info (User ID)
   - Online status
   - Complete & Next button (right-aligned)

## Queue Logic Flow

### User Perspective
1. User clicks "Talk to Agent" button
2. System checks for existing queue entry
3. If new: Assigns queue position, creates entry
4. Shows queue position: "#X in line"
5. Polls every 3s for status updates
6. When admin accepts: Status changes to 'active'
7. Shows "Agent Connected!" notification
8. Enables messaging
9. User chats with agent
10. When admin completes: Status changes to 'completed'
11. Shows "Conversation Ended" notification
12. Header resets to "Barangay Chatbot"
13. Polling stops automatically
14. Message input is disabled

### Admin Perspective
1. Opens admin chatbot
2. Sees "X Users Waiting" if queue has users
3. Clicks "Accept Next User"
4. First user in queue becomes active
5. Conversation loads automatically
6. Chats with user
7. Clicks "Complete & Next"
8. Current conversation marked completed
9. Next user automatically loaded
10. If no more users: Returns to inbox

## Testing Checklist

### User Tests
- [ ] Join queue and see position
- [ ] Queue position updates as others are served
- [ ] Cannot send messages while waiting
- [ ] Receives notification when agent connects
- [ ] Can send messages when active
- [ ] Receives agent responses

### Admin Tests
- [ ] See queue count on dashboard
- [ ] Accept next user works
- [ ] Cannot accept if already has active conversation
- [ ] Can send/receive messages
- [ ] Complete & Next transitions smoothly
- [ ] Queue count updates correctly
- [ ] Returns to inbox when queue empty

### Edge Cases
- [ ] User closes browser while in queue
- [ ] Admin closes browser during conversation
- [ ] Multiple admins accepting users
- [ ] Network interruption during conversation
- [ ] Very long queue (10+ users)

## Files Modified

### Controllers
- `app/Http/Controllers/AgentConversationController.php`
- `app/Http/Controllers/Admin/AgentConversationController.php`

### Models
- `app/Models/AgentConversation.php`

### Routes
- `routes/web.php`

### Frontend
- `public/js/chatbot.js`
- `public/js/admin-chatbot.js`

### Views
- `resources/views/layouts/admin/master.blade.php`

### Migrations
- `database/migrations/2025_10_05_000002_add_missing_queue_fields_to_agent_conversations.php`

## Configuration

No additional configuration needed. The system uses existing database and authentication setup.

## Performance Considerations

1. **Polling Interval**: 3 seconds (adjustable in code)
2. **Queue Position Calculation**: Indexed query, O(1) complexity
3. **Message Filtering**: Uses database indexes for fast filtering
4. **Duplicate Prevention**: Timestamp-based checking

## Future Enhancements (Optional)

1. **Priority Queue**: VIP or urgent users jump queue
2. **Estimated Wait Time**: Calculate based on average conversation time
3. **Admin Status**: Show admin as "busy" or "available"
4. **Queue Abandonment**: Auto-remove users who wait too long
5. **Chat Transfer**: Transfer conversation to another admin
6. **Conversation Rating**: Users rate support after completion
7. **Analytics Dashboard**: Track queue metrics and admin performance
8. **Push Notifications**: Notify users when their turn is near
9. **Multiple Queues**: Separate queues by department/topic
10. **Canned Responses**: Quick reply templates for admins

## Notes

- System handles only ONE active conversation per admin at a time
- Queue is FIFO (First In, First Out)
- All operations are handled in the floating chatbot
- No page refresh required for queue updates
- Automatic fallback if network issues occur
