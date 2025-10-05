# Queue System - User Experience Flow

## Visual Flow Diagram

```
USER JOURNEY
═══════════════════════════════════════════════════════════════════════

Step 1: USER JOINS QUEUE
┌─────────────────────────────────────┐
│  💬 Barangay Chatbot                │
├─────────────────────────────────────┤
│                                     │
│  ⏳ You're in the Queue             │
│  ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━  │
│  You are #3 in line to talk to an  │
│  agent. Please wait while an agent │
│  becomes available.                 │
│                                     │
│  ╔════════════════════════════╗   │
│  ║ Queue Position: 3          ║   │
│  ╚════════════════════════════╝   │
│                                     │
│  [Trying to send message...]        │
│  ⚠️  Please wait for your turn.    │
│      You are #3 in line.            │
└─────────────────────────────────────┘

Status: WAITING (Yellow Badge)
Actions: ❌ Cannot send messages
Polling: ✓ Checking queue status every 3s


Step 2: QUEUE POSITION UPDATES
┌─────────────────────────────────────┐
│  💬 Barangay Chatbot                │
├─────────────────────────────────────┤
│                                     │
│  ⏳ You're in the Queue             │
│  ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━  │
│  You are #2 in line to talk to an  │
│  agent. Please wait while an agent │
│  becomes available.                 │
│                                     │
│  ╔════════════════════════════╗   │
│  ║ Queue Position: 2 ← Updated!║   │
│  ╚════════════════════════════╝   │
└─────────────────────────────────────┘

Status: WAITING (Yellow Badge)
Position: Automatically updated as people are served


Step 3: AGENT CONNECTS
┌─────────────────────────────────────┐
│  💬 Talking to Agent                │
├─────────────────────────────────────┤
│                                     │
│  ✅ Agent Connected!                │
│  ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━  │
│  An agent is now available.         │
│  You can start chatting!            │
│                                     │
│  [Chat is now enabled]              │
│  You: Hello, I need help            │
│  Agent: Hi! How can I help you?     │
│                                     │
│  Type your message...          [>]  │
└─────────────────────────────────────┘

Status: ACTIVE (Green Badge)
Actions: ✓ Can send messages
Header: Changed to "Talking to Agent"


Step 4: CONVERSATION ENDS (Admin clicks "Complete & Next")
┌─────────────────────────────────────┐
│  💬 Barangay Chatbot                │
├─────────────────────────────────────┤
│                                     │
│  Agent: Thank you for contacting us │
│                                     │
│  ✓ Conversation Ended               │
│  ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━  │
│  The agent has ended this           │
│  conversation. Thank you for        │
│  contacting us!                     │
│                                     │
│  If you have more questions, feel   │
│  free to start a new conversation.  │
│                                     │
│  [Trying to send message...]        │
│  ⚠️  This conversation has ended.  │
│      Please start a new one.        │
└─────────────────────────────────────┘

Status: COMPLETED (Blue Badge)
Actions: ❌ Cannot send messages
Header: Reset to "Barangay Chatbot"
Polling: ❌ Stopped automatically
```

---

## Admin Experience Flow

```
ADMIN JOURNEY
═══════════════════════════════════════════════════════════════════════

Step 1: VIEW QUEUE
┌─────────────────────────────────────┐
│  💬 Messages                    [×] │
├─────────────────────────────────────┤
│                                     │
│  👥  3 Users Waiting                │
│  ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━  │
│                                     │
│  Users are waiting to talk to       │
│  an agent                           │
│                                     │
│      ┌──────────────────────┐      │
│      │ ✅ Accept Next User  │      │
│      └──────────────────────┘      │
│                                     │
└─────────────────────────────────────┘


Step 2: ACTIVE CONVERSATION
┌─────────────────────────────────────┐
│  💬 Messages                    [×] │
├─────────────────────────────────────┤
│  💬 Active Conversation    [2 in Q] │
│  ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━  │
│                                     │
│  👤 User abc12345      Online   [✓ Complete & Next] │
│  ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━  │
│                                     │
│  User: Hello, I need help           │
│  You: Hi! How can I help you?       │
│  User: I need a certificate         │
│  You: Sure, I can help with that    │
│                                     │
│  Type your message...          [>]  │
└─────────────────────────────────────┘

Features:
- Shows queue count (2 in Q)
- "Complete & Next" button always visible
- Can chat with user


Step 3: CLICK COMPLETE & NEXT
┌─────────────────────────────────────┐
│  Complete Conversation?             │
│  ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━  │
│  Are you sure you want to complete  │
│  this conversation and move to the  │
│  next user?                         │
│                                     │
│     [Cancel]      [Confirm]         │
└─────────────────────────────────────┘


Step 4: AUTOMATICALLY LOAD NEXT USER
┌─────────────────────────────────────┐
│  💬 Messages                    [×] │
├─────────────────────────────────────┤
│  💬 Active Conversation    [1 in Q] │
│  ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━  │
│                                     │
│  👤 User def67890      Online   [✓ Complete & Next] │
│  ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━  │
│                                     │
│  You: Connected to next user.       │
│  User: Hi, I need information...    │
│                                     │
│  Type your message...          [>]  │
└─────────────────────────────────────┘

Result:
- Previous conversation completed
- Next user loaded automatically
- Queue count decreased (2 → 1)
```

---

## Status Indicators

### User Side

| Status | Badge Color | Header | Can Send Messages | Polling |
|--------|-------------|--------|-------------------|---------|
| **Waiting** | 🟡 Yellow | "Barangay Chatbot" | ❌ No | ✓ Every 3s |
| **Active** | 🟢 Green | "Talking to Agent" | ✓ Yes | ✓ Every 3s |
| **Completed** | 🔵 Blue | "Barangay Chatbot" | ❌ No | ❌ Stopped |

### Admin Side

| State | Display | Actions Available |
|-------|---------|-------------------|
| **No Queue** | "No Users Waiting" | None |
| **Has Queue** | "X Users Waiting" | Accept Next User |
| **Active** | "Active Conversation" + Queue Count | Complete & Next |

---

## Key Notifications

### User Receives:

1. **Joined Queue** (Yellow)
   ```
   ⏳ You're in the Queue
   You are #X in line
   [Real-time position counter]
   ```

2. **Agent Connected** (Green)
   ```
   ✅ Agent Connected!
   An agent is now available
   You can start chatting!
   ```

3. **Conversation Ended** (Blue)
   ```
   ✓ Conversation Ended
   The agent has ended this conversation
   Thank you for contacting us!
   If you have more questions, feel free to start a new conversation
   ```

### Admin Sees:

1. **Queue Count**
   ```
   [3 in queue] → Shows in header
   ```

2. **User Connected**
   ```
   "Connected to next user in queue"
   ```

3. **No More Users**
   ```
   "No more users in queue"
   → Returns to inbox
   ```

---

## Automatic Behaviors

### For Users:
✓ Queue position updates automatically every 3 seconds
✓ Notification appears when agent connects
✓ Notification appears when conversation ends
✓ Header changes to reflect conversation state
✓ Message input blocked/enabled based on status
✓ Polling stops after conversation completion

### For Admins:
✓ Queue count updates in real-time
✓ Next user loads automatically after completing
✓ Cannot accept new user if already in conversation
✓ Smooth transition between users
✓ Returns to inbox when queue is empty

---

## Error Prevention

### User Cannot:
❌ Send messages while waiting in queue
❌ Send messages after conversation ends
❌ Join queue twice (uses existing session)

### Admin Cannot:
❌ Accept multiple users simultaneously
❌ Send messages to completed conversations

---

## Technical Details

### Polling Frequency
- **3 seconds** for both queue status and new messages
- Automatically stops when conversation completes

### Queue Order
- **FIFO** (First In, First Out)
- Position calculated dynamically
- Updates in real-time as people are served

### Session Management
- Each conversation has unique `session_id`
- User identified by `user_session` (browser-based)
- Admin identified by `assigned_admin_id`

---

## Benefits of This Implementation

1. **For Users:**
   - ✓ Know exactly where they are in line
   - ✓ See when they move up in queue
   - ✓ Clear notification when agent is ready
   - ✓ Clear notification when conversation ends
   - ✓ No confusion about conversation state

2. **For Admins:**
   - ✓ Focus on one user at a time
   - ✓ See how many are waiting
   - ✓ Easy workflow with auto-next
   - ✓ Clean interface showing active user
   - ✓ Queue count always visible

3. **For System:**
   - ✓ Organized queue management
   - ✓ No lost conversations
   - ✓ Efficient resource utilization
   - ✓ Proper status tracking
   - ✓ Database-backed persistence
