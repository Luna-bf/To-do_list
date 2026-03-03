const password = prompt('Please enter the password');

fetch('/test.php', {
  method: 'POST',
  body: JSON.stringify({ // Send the object as JSON
    password: password
  })
})
.then(response => response.text()) // Decode the response as text.
.then(text => {
  console.log(text); // Log the received text.
});