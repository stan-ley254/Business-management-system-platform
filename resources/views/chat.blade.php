<form id="chat-form">
  <input type="text" id="userPrompt" class="form-control" placeholder="Ask your assistant...">
  <button type="submit" class="btn btn-primary mt-2">Send</button>
</form>

<div id="chat-response" class="mt-3"></div>

<script>
document.getElementById('chat-form').addEventListener('submit', async function(e) {
    e.preventDefault();
    const prompt = document.getElementById('userPrompt').value;
    const responseBox = document.getElementById('chat-response');
    responseBox.innerText = "Thinking...";

    const res = await fetch("/huggingface/query", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": '{{ csrf_token() }}'
        },
        body: JSON.stringify({ prompt })
    });

    const data = await res.json();
    responseBox.innerText = data.response || data.error || "Something went wrong.";
});
</script>
