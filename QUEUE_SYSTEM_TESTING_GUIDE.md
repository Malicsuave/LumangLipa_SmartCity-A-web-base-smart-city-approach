# Queue System Testing Guide

## Quick Test Steps

### Setup
1. Open browser in **normal mode** (for User)
2. Open browser in **incognito/private mode** (for Admin)
3. Login as admin in incognito window
4. Keep both windows side by side

---

## Test 1: Basic Queue Entry

### User Window (Normal)
1. Click the floating chatbot button (bottom right)
2. Type any question and send
3. Click "Talk to Agent" button when offered
4. **Expected**: See yellow box with "You are #1 in line"
5. **Expected**: See "Queue Position: 1" indicator
6. Try to send a message
7. **Expected**: See warning "Please wait for your turn. You are #1 in line"

### Admin Window (Incognito)
1. Click the admin chatbot button (bottom right)
2. **Expected**: See "1 User Waiting"
3. **Expected**: See "Accept Next User" button
4. Click "Accept Next User"
5. **Expected**: Chat view opens with user conversation
6. **Expected**: See "Complete & Next" button in header

### User Window (Normal)
1. **Expected**: See green box "✅ Agent Connected!"
2. **Expected**: Header changes to "💬 Talking to Agent"
3. Type a message and send
4. **Expected**: Message sends successfully

### Admin Window (Incognito)
1. **Expected**: See user's message appear
2. Type a reply and send
3. **Expected**: Message sends successfully

### User Window (Normal)
1. **Expected**: See admin's reply appear

---

## Test 2: Multiple Users in Queue

### Setup Multiple Users
1. Open 3 more browser windows (or tabs in incognito mode)
2. In each window:
   - Open the chatbot
   - Click "Talk to Agent"
   - Note the queue position

**Expected Queue Positions:**
- Window 1: #2 (Window 1 from Test 1 is #1 and active)
- Window 2: #3
- Window 3: #4

### Admin Completes First User
1. In admin window, click "Complete & Next"
2. **Expected**: Conversation with Window 1 ends
3. **Expected**: Automatically opens Window 2's conversation
4. **Expected**: Window 2 shows "✅ Agent Connected!"
5. **Expected**: Window 3 and 4 positions update to #2 and #3

---

## Test 3: Queue Position Updates

### Real-time Position Updates
1. Keep 2 users waiting in queue
2. Watch the queue position numbers
3. **Expected**: Every 3 seconds, positions are checked
4. As admin completes conversations, positions should update automatically

---

## Test 4: Admin Queue Management

### Admin Dashboard
1. In admin window with no active conversation:
2. **Expected**: See "X Users Waiting" (where X is queue count)
3. **Expected**: See "Accept Next User" button
4. Click "Accept Next User"
5. **Expected**: Opens conversation with next user
6. Try clicking "Accept Next User" again
7. **Expected**: Error or blocked (admin can only have 1 active conversation)

### Complete & Next Button
1. With active conversation:
2. **Expected**: See green "Complete & Next" button in header
3. Click the button
4. **Expected**: Confirmation dialog appears
5. Confirm
6. **Expected**: Current conversation completes
7. **Expected**: Next user loads automatically
8. If no more users: **Expected**: Returns to inbox view

---

## Test 5: Edge Cases

### User Closes Browser While Waiting
1. User joins queue (#3 in line)
2. Close user's browser
3. **Expected**: Queue position remains (conversation is not cleaned up automatically)
4. Note: User won't receive messages, but position stays until admin accepts

### Admin Closes Browser During Conversation
1. Admin accepts user
2. Close admin browser
3. Reopen and login
4. **Expected**: Conversation may still show as "active"
5. **Expected**: Admin can send messages to continue

### Empty Queue
1. Admin completes all conversations
2. **Expected**: Shows "No Users Waiting" with empty inbox icon
3. **Expected**: No "Accept Next User" button

---

## Test 6: Message Flow

### User → Admin
1. User sends: "Hello, I need help"
2. **Expected**: Appears immediately in user's chat
3. **Expected**: Appears in admin's chat within 3 seconds (polling interval)

### Admin → User
1. Admin sends: "Hi! How can I help you?"
2. **Expected**: Appears immediately in admin's chat
3. **Expected**: Appears in user's chat within 3 seconds (polling interval)

---

## Test 7: Conversation End Notification

### User Receives End Notification
1. User is in active conversation with admin
2. Both are exchanging messages
3. In admin window, click "Complete & Next" button
4. Confirm the action

### User Window (Should Show):
1. **Expected**: Blue notification box appears
2. **Expected**: Shows "✓ Conversation Ended"
3. **Expected**: Message says "The agent has ended this conversation"
4. **Expected**: Shows "Thank you for contacting us!"
5. **Expected**: Suggests starting new conversation
6. **Expected**: Header changes back to "💬 Barangay Chatbot"
7. **Expected**: Message input still visible but messages won't send

### Try Sending Message After End
1. User tries to type and send a message
2. **Expected**: Shows warning: "This conversation has ended. Please start a new conversation if you need more help."
3. **Expected**: Message is not sent

### Verify Polling Stopped
1. Open browser Network tab
2. After conversation ends, wait 10 seconds
3. **Expected**: No more requests to `/queue-status` or `/new-messages`
4. **Expected**: Polling has stopped

---

## Test 8: Multiple Status Transitions

### Complete Flow Test
1. **User joins queue** → See "You are #1 in line" (Waiting)
2. **Admin accepts** → See "✅ Agent Connected!" (Active)
3. **Exchange messages** → Messages flow both ways
4. **Admin completes** → See "✓ Conversation Ended" (Completed)
5. **User refreshes page** → Chatbot resets, can start fresh conversation

### Expected Status Changes:
- `waiting` → Yellow badge, blocked messages
- `active` → Green badge, enabled messages
- `completed` → Blue notification, blocked messages, polling stopped

---

## Troubleshooting

### Queue Position Not Updating
- Check browser console for errors
- Verify `/api/agent-conversation/{sessionId}/queue-status` is responding
- Check polling is working (should see requests every 3 seconds in Network tab)

### Messages Not Appearing
- Check `/api/agent-conversation/{sessionId}/new-messages` endpoint
- Verify CSRF token is present
- Check Laravel logs: `storage/logs/laravel.log`

### Accept Next User Not Working
- Verify admin is logged in
- Check admin doesn't already have active conversation
- Check queue has users: `/api/admin/agent-conversation/active`

### Complete & Next Not Working
- Check conversation is active
- Verify session_id is correct
- Check for JavaScript console errors

---

## Expected Database States

### User Waiting in Queue
```sql
SELECT * FROM agent_conversations WHERE queue_status = 'waiting';
```
Should show records with:
- `queue_status` = 'waiting'
- `queue_position` = their position number
- `queued_at` = timestamp when joined
- `assigned_admin_id` = NULL

### Active Conversation
```sql
SELECT * FROM agent_conversations WHERE queue_status = 'active';
```
Should show records with:
- `queue_status` = 'active'
- `assigned_admin_id` = admin's user ID
- `assigned_at` = timestamp when activated

### Completed Conversation
```sql
SELECT * FROM agent_conversations WHERE queue_status = 'completed';
```
Should show records with:
- `queue_status` = 'completed'
- `conversation_completed_at` = timestamp when completed
- `is_active` = 0 (false)

---

## Performance Checks

### Polling Frequency
- Open browser Network tab
- Count requests to `/queue-status` and `/new-messages`
- **Expected**: 1 request every 3 seconds

### Database Queries
Monitor database queries:
```bash
php artisan telescope
```
- Check queries are using indexes
- Verify no N+1 query issues
- Queue position calculation should be fast (<50ms)

---

## Success Criteria

✅ Users can join queue and see their position  
✅ Queue position updates in real-time  
✅ Users cannot send messages while waiting  
✅ Admin can only handle 1 conversation at a time  
✅ Admin can see queue count  
✅ Admin can accept next user  
✅ Admin can complete and move to next  
✅ Messages flow bidirectionally  
✅ System handles empty queue gracefully  
✅ UI updates reflect queue status changes  
✅ User receives notification when conversation ends  
✅ Polling stops after conversation completion  
✅ User cannot send messages after conversation ends  

---

## Common Issues & Solutions

### Issue: Queue position shows but doesn't decrease
**Solution**: Check if admins are actually accepting and completing conversations

### Issue: "Accept Next User" does nothing
**Solution**: Verify admin doesn't already have an active conversation

### Issue: Messages delayed or not appearing
**Solution**: Check polling interval, verify endpoints are responding

### Issue: User stuck in queue
**Solution**: Admin needs to accept them, or manually update database:
```sql
UPDATE agent_conversations 
SET queue_status = 'cancelled' 
WHERE session_id = 'xxx';
```

### Issue: Multiple admins see same queue count
**Solution**: This is expected - any admin can accept the next user

---

## Browser Console Commands (For Debugging)

### Check User Chatbot State
```javascript
console.log('Queue Position:', window.barangayChatbot.queuePosition);
console.log('Queue Status:', window.barangayChatbot.queueStatus);
console.log('Agent Session:', window.barangayChatbot.agentSessionId);
```

### Check Admin Chatbot State
```javascript
console.log('Current Conversation:', window.adminChatbot.currentConversationId);
console.log('View:', window.adminChatbot.currentView);
```

### Force Queue Status Check
```javascript
// User side
window.barangayChatbot.checkQueueStatus();

// Admin side
window.adminChatbot.loadActiveConversations();
```

---

## Clean Up After Testing

### Reset Queue
```sql
-- Clear all conversations
TRUNCATE agent_conversations;

-- Or mark all as completed
UPDATE agent_conversations SET queue_status = 'completed', is_active = 0;
```

### Reset Specific User
```sql
UPDATE agent_conversations 
SET queue_status = 'cancelled', is_active = 0 
WHERE user_session = 'USER_SESSION_HERE';
```
