<!DOCTYPE html>
<html lang="en">
<head>
    @include('admin.css')
    <style>
        .chat-container {
            max-width: 700px;
            margin: 20px auto;
            background: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .chat-box {
            height: 400px;
            overflow-y: auto;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            background: white;
            margin-bottom: 15px;
        }
        .message {
            margin: 10px 0;
            padding: 10px 15px;
            border-radius: 15px;
            max-width: 80%;
            clear: both;
        }
        .user-message {
            background: #007bff;
            color: white;
            float: right;
            text-align: right;
        }
        .bot-message {
            background: #e9ecef;
            color: #333;
            float: left;
        }
        .upload-box {
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
@include('admin.sidebar')
@include('admin.header')

<div class="main-panel">
    <div class="content-wrapper">
      <div class="container card">
          <h2 class="mb-3">Smart Business Analyst</h2>
        <div class="chat-container">
          

            <!-- Upload Sales -->
            <form method="POST" action="{{ route('sales.upload') }}" enctype="multipart/form-data" class="upload-box">
                @csrf
                <div class="input-group">
                    <input type="file" name="sales_file" class="form-control" required>
                    <button class="btn btn-primary">Upload Sales CSV</button>
                </div>
            </form>

            <!-- Chat Window -->
            <div class="chat-box" id="chatBox">
                <!-- Messages will be added dynamically -->
            </div>

            <!-- Input -->
            <form id="chatForm">
                @csrf
                <div class="input-group">
                    <input type="text" id="question" name="question" class="form-control" placeholder="Ask about your sales..." required>
                    <button class="btn btn-success" type="submit">Send</button>
                </div>
            </form>

            <div id="chatAlert"></div>
        </div>
        </div>
    </div>
</div>

@include('admin.script')
    <script>
document.addEventListener("DOMContentLoaded", () => {
    const chatForm = document.getElementById("chatForm");
    const chatBox = document.getElementById("chatBox");
    const chatAlert = document.getElementById("chatAlert");

    chatForm.addEventListener("submit", async (e) => {
        e.preventDefault();
        let question = document.getElementById("question").value;

        // Add user message
        chatBox.innerHTML += `<div class="message user-message">${question}</div>`;
        chatBox.scrollTop = chatBox.scrollHeight;
        document.getElementById("question").value = "";

        try {
            let response = await fetch("{{ route('sales.ask') }}", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({ question })
            });

            let data = await response.json();

            if (data.answer) {
                chatBox.innerHTML += `<div class="message bot-message">${data.answer}</div>`;
            } else if (data.error) {
                chatAlert.innerHTML = `<div class="alert alert-danger">${data.error}</div>`;
            }
        } catch (err) {
            chatAlert.innerHTML = `<div class="alert alert-danger">Error: ${err.message}</div>`;
        }

        chatBox.scrollTop = chatBox.scrollHeight;
    });
});
</script>
</body>
</html>
