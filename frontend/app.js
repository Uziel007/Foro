function cargarPublicaciones() {
    fetch('../backend/obtener_posts.php')
        .then(response => response.json())
        .then(data => {
            let usuarioId = data.usuario_id;
            let postsHtml = '';

            // Mostrar información del usuario logueado
            if (usuarioId) {
                document.getElementById('userName').innerText = `Bienvenido, ${data.posts.find(p => p.usuario_id == usuarioId)?.nombre || "Usuario"}`;
                document.getElementById('userAvatar').src = data.posts.find(p => p.usuario_id == usuarioId)?.imagen || "img/default-avatar.png";
                document.getElementById('userAvatar').classList.add('usuario-logueado');
            }

            // Generar publicaciones
            data.posts.forEach(post => {
                let esUsuarioLogueado = usuarioId == post.usuario_id; // Corregido aquí
                let claseUsuario = esUsuarioLogueado ? "usuario-logueado" : "";

                let botonesReaccion = usuarioId 
                    ? `<button class="btn-like" onclick="reaccionar(${post.id}, 'like')">Like <span>(${post.likes})</span></button>
                       <button class="btn-dislike" onclick="reaccionar(${post.id}, 'dislike')">Dislike <span>(${post.dislikes})</span></button>`
                    : `<p>Inicia sesión para reaccionar</p>`;

                postsHtml += 
                    `<div class="post ${claseUsuario}">
                        <div class="post-header">
                            <img class="avatar ${claseUsuario}" src="${post.imagen}" alt="Avatar de ${post.nombre}">
                            <h3>${post.tema} - <small>${post.nombre}</small></h3>
                        </div>
                        <p>${post.contenido}</p>
                        <div class="reaction-buttons">${botonesReaccion}</div>
                    </div>`;
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
