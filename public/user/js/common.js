document.addEventListener("DOMContentLoaded", function () {

    const form = document.getElementById('resetForm');

    if (form) {

        form.addEventListener('submit', function (e) {

            let password = document.getElementById('password').value;
            let confirmPassword = document.getElementById('confirmPassword').value;

            let passwordError = document.getElementById('passwordError');
            let confirmPasswordError = document.getElementById('confirmPasswordError');

            // Clear old errors
            passwordError.innerText = '';
            confirmPasswordError.innerText = '';

            let isValid = true;

            if (password === '') {
                passwordError.innerText = 'Password is required.';
                isValid = false;
            } else if (password.length < 6) {
                passwordError.innerText = 'Password must be at least 6 characters.';
                isValid = false;
            }

            if (confirmPassword === '') {
                confirmPasswordError.innerText = 'Confirm password is required.';
                isValid = false;
            } else if (password !== confirmPassword) {
                confirmPasswordError.innerText = 'Passwords do not match.';
                isValid = false;
            }

            if (!isValid) {
                e.preventDefault();
            }

        });

    }

});


document.addEventListener("DOMContentLoaded", function () {

    document.querySelectorAll(".map-preview").forEach(function (map) {

        map.addEventListener("click", function () {
           
            let lat = this.getAttribute("data-lat");
            let lng = this.getAttribute("data-lng");

            let mapUrl = `https://maps.google.com/maps?q=${lat},${lng}&z=15&output=embed`;

          
            document.getElementById("commonheader").innerText = "Location Map";

        
            document.getElementById("commonModalBody").innerHTML =
                `<iframe width="100%" height="400" style="border:0;" 
                 src="${mapUrl}" allowfullscreen></iframe>`;

            var modal = new bootstrap.Modal(document.getElementById('commonModal'));
            modal.show();
        });

    });

});
 const items = Array.from(document.querySelectorAll('#galleryGrid .gallery-item'));

         let current = 0;
         
         // Build lightbox thumbnail strip by reading each HTML item's <img>
         const thumbsEl = document.getElementById('lbThumbs');
         items.forEach(function(item, i) {
           const img   = item.querySelector('img');
           const thumb = document.createElement('img');
           thumb.className    = 'lb-thumb';
           thumb.src          = img.src;
           thumb.alt          = img.alt;
           thumb.dataset.index = i;
           thumbsEl.appendChild(thumb);
         });
         
         function openLightbox(index) {
           current = index;
           document.getElementById('lightbox').classList.add('active');
           document.body.style.overflow = 'hidden';
            const images = document.querySelectorAll('.gallery-image');

          if (images.length <= 1) {
              document.getElementById('lbPrev').style.display = 'none';
              document.getElementById('lbNext').style.display = 'none';
          } else {
              document.getElementById('lbPrev').style.display = 'block';
              document.getElementById('lbNext').style.display = 'block';
          }
           updateLightbox();
         }
         
         function closeLightbox() {
           document.getElementById('lightbox').classList.remove('active');
           document.body.style.overflow = '';
            resetZoom();
         }
         function updateLightbox() {

            resetZoom();   // 👈 ADD THIS LINE AT TOP

            var item  = items[current];
            var img   = item.querySelector('img');
            var title = item.dataset.title || img.alt;

            var lbImg = document.getElementById('lbImg');
            lbImg.src = '';
            lbImg.src = img.src;
            lbImg.alt = title;

            document.getElementById('lbTitle').textContent   = title;
            document.getElementById('lbCounter').textContent = (current + 1) + ' / ' + items.length;

            document.querySelectorAll('.lb-thumb').forEach(function(el, i) {
              el.classList.toggle('active', i === current);
            });

            var activeThumb = thumbsEl.querySelector('.lb-thumb.active');
            if (activeThumb) {
              activeThumb.scrollIntoView({ behavior: 'smooth', inline: 'center', block: 'nearest' });
            }
          }
         function updateLightboxbk() {
           var item  = items[current];
           var img   = item.querySelector('img');
           var title = item.dataset.title || img.alt;
         
           // Swap src to re-trigger CSS animation
           var lbImg = document.getElementById('lbImg');
           lbImg.src = '';
           lbImg.src = img.src;
           lbImg.alt = title;
         
           document.getElementById('lbTitle').textContent   = title;
           document.getElementById('lbCounter').textContent = (current + 1) + ' / ' + items.length;
         
           // Sync thumbnails
           document.querySelectorAll('.lb-thumb').forEach(function(el, i) {
             el.classList.toggle('active', i === current);
           });
         
           // Scroll active thumb into view
           var activeThumb = thumbsEl.querySelector('.lb-thumb.active');
           if (activeThumb) {
             activeThumb.scrollIntoView({ behavior: 'smooth', inline: 'center', block: 'nearest' });
           }
         }
         
         // Click on a gallery item → open lightbox
         document.getElementById('galleryGrid').addEventListener('click', function(e) {

            if (e.target.closest('.download-icon')) {
        e.stopImmediatePropagation(); // stop ALL other handlers
        return; // allow normal anchor behavior
    }

           var item = e.target.closest('.gallery-item');
           if (item) openLightbox(items.indexOf(item));
         });
         
         document.getElementById('lbClose').addEventListener('click', closeLightbox);
         
         document.getElementById('lbPrev').addEventListener('click', function() {
           current = (current - 1 + items.length) % items.length;
           updateLightbox();
         });
         
         document.getElementById('lbNext').addEventListener('click', function() {
           current = (current + 1) % items.length;
           updateLightbox();
         });
         
         // Click a thumbnail
         thumbsEl.addEventListener('click', function(e) {
           var thumb = e.target.closest('.lb-thumb');
           if (thumb) { current = parseInt(thumb.dataset.index); updateLightbox(); }
         });
         
         // Keyboard navigation
         document.addEventListener('keydown', function(e) {
           if (!document.getElementById('lightbox').classList.contains('active')) return;
           if (e.key === 'ArrowRight') { current = (current + 1) % items.length; updateLightbox(); }
           if (e.key === 'ArrowLeft')  { current = (current - 1 + items.length) % items.length; updateLightbox(); }
           if (e.key === 'Escape') closeLightbox();
         });
         
         // Click backdrop to close
         document.getElementById('lbMain').addEventListener('click', function(e) {
           if (e.target === this) closeLightbox();
         });

         	const img = document.getElementById("lbImg");
const container = document.getElementById("lbMain");

let scale = 1;
let isDragging = false;
let startX, startY;
let translateX = 0;
let translateY = 0;

// Zoom with mouse wheel
container.addEventListener("wheel", function(e) {
    e.preventDefault();

    if (e.deltaY < 0) {
        scale += 0.2;
    } else {
        scale -= 0.2;
    }

    scale = Math.min(Math.max(1, scale), 5); // Min 1x, Max 5x

    updateTransform();
});

// Double click zoom
img.addEventListener("dblclick", function() {
    if (scale === 1) {
        scale = 2;
    } else {
        scale = 1;
        translateX = 0;
        translateY = 0;
    }
    updateTransform();
});

// Drag start
container.addEventListener("mousedown", function(e) {
    if (scale <= 1) return;

    isDragging = true;
    startX = e.clientX - translateX;
    startY = e.clientY - translateY;
    container.style.cursor = "grabbing";
});

// Drag move
container.addEventListener("mousemove", function(e) {
    if (!isDragging) return;

    translateX = e.clientX - startX;
    translateY = e.clientY - startY;

    updateTransform();
});

// Drag end
container.addEventListener("mouseup", function() {
    isDragging = false;
    container.style.cursor = "grab";
});

container.addEventListener("mouseleave", function() {
    isDragging = false;
    container.style.cursor = "grab";
});

function updateTransform() {
    img.style.transform = `translate(${translateX}px, ${translateY}px) scale(${scale})`;
}

// Reset when image changes
function resetZoom() {
    scale = 1;
    translateX = 0;
    translateY = 0;
    updateTransform();
}


         