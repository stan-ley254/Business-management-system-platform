@include('navstore')
 
 <div class="custom-container">
    <div class="custom-header">
                Chat with the Business
            </div>
            <!-- Chat Window -->
            <div class="chat-box" id="chatBox">
                <!-- Messages will be added dynamically -->
            </div>

            <!-- Input -->
            <form id="chatForm">
                @csrf
                <div class="input-group">
                    <input type="text" id="question" name="question" class="form-control" placeholder="Ask about your business..." required>
                    <button class="btn btn-success" type="submit">Send</button>
                </div>
            </form>

            <div id="chatAlert"></div>
        </div>
<script src="{{ asset('/js/scriptsfiles.js') }}"></script>
<script src="{{ asset('/resources/js/bootstrap.js') }}"></script>

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