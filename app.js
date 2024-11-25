document.querySelector('#btnAddComment').addEventListener('click', function() {
    let postId = this.dataset.postid; // Get the postId from the button's data attribute
    let text = document.querySelector('#commentText').value; // Get the comment text

    // Post to the server (ajax)
    let formData = new FormData();
    formData.append('text', text);
    formData.append('postId', postId);

    fetch('ajax/savecomment.php', {
        method: 'POST',
        body: formData
    })
        .then(response => response.json())
        .then(result => {
            if (result.status === 'success') {
                // If the comment is saved successfully, add it to the list
                let newComment = document.createElement('li');
                newComment.innerHTML = `<strong>You:</strong> ${text}`; // Show the comment text with a username
                document.querySelector('.post__comments__list').appendChild(newComment);
            } else {
                alert('Error saving comment: ' + result.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
});
