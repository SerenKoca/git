
document.querySelector('#btnAddComment').addEventListener('click', function (e) {
    e.preventDefault(); // Zorg ervoor dat de link niet volgt
    let postId = this.dataset.postid;
    let text = document.querySelector('#commentText').value;

    if (text.trim() === '') {
        alert('Comment text cannot be empty');
        return;
    }

    let formData = new FormData();
    formData.append('text', text);
    formData.append('productId', postId);

    fetch('ajax/savecomment.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(result => {
        if (result.status === 'success') {
            let newComment = document.createElement('li');
            newComment.textContent = result.body;
            document.querySelector('.post__comments__list').appendChild(newComment);
            document.querySelector('#commentText').value = ''; // Maak het invoerveld leeg
        } else {
            alert(result.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
});