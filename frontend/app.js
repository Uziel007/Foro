// Cargar publicaciones en tiempo real
function cargarPublicaciones() {
    fetch('../backend/obtener_posts.php')
        .then(response => response.json())
        .then(data => {
            let postsHtml = '';
            data.forEach(post => {
                postsHtml += `
                    <div class="post">
                        <h3>${post.tema} - <small>${post.nombre}</small></h3>
                        <p>${post.contenido}</p>
                        <button onclick="reaccionar(${post.id}, 'like')">👍 (${post.likes})</button>
                        <button onclick="reaccionar(${post.id}, 'dislike')">👎 (${post.dislikes})</button>
                    </div>
                `;
            });
            document.getElementById('postsContainer').innerHTML = postsHtml;
        });
}

// Crear nueva publicación
document.getElementById('formPost').addEventListener('submit', function (e) {
    e.preventDefault();
    const formData = new FormData(this);
    fetch('../backend/crear_post.php', {
        method: 'POST',
        body: formData
    }).then(response => response.json()).then(data => {
        if (data.success) {
            cargarPublicaciones(); // Actualizar en tiempo real
        } else {
            alert(data.message);
        }
    });
});

// Reaccionar a una publicación
function reaccionar(publicacion_id, tipo) {
    fetch('../backend/reaccionar.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `publicacion_id=${publicacion_id}&tipo=${tipo}`
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                cargarPublicaciones(); // Actualizar en tiempo real
            } else {
                alert(data.message);
            }
        });
}

// Actualizar publicaciones cada 5 segundos
setInterval(cargarPublicaciones, 5000);
cargarPublicaciones();
