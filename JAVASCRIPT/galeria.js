document.addEventListener('DOMContentLoaded', () => {
    const galleryItems = document.querySelectorAll('.gallery img, .gallery video');
    const modal = document.querySelector('.modal');
    const expandedContent = document.getElementById('expandedContent');
    const closeBtn = document.querySelector('.close');
    const prevBtn = document.querySelector('.prev');
    const nextBtn = document.querySelector('.next');
    let currentIndex = 0;
    
    function showModal(index) {
        currentIndex = index;
        const item = galleryItems[currentIndex];

        // Verifica se é uma imagem ou vídeo
        if (item.tagName === 'IMG') {
            expandedContent.innerHTML = `<img src="${item.src}" alt="${item.alt}" class="modal-image">`;
        } else if (item.tagName === 'VIDEO') {
            expandedContent.innerHTML = `<video src="${item.src}" controls class="modal-video"></video>`;
        }

        modal.style.display = 'flex';
    }

    function closeModal() {
        modal.style.display = 'none';
        expandedContent.innerHTML = ''; // Limpa o conteúdo ao fechar
    }

    function showNext() {
        currentIndex = (currentIndex + 1) % galleryItems.length;
        showModal(currentIndex);
    }

    function showPrev() {
        currentIndex = (currentIndex - 1 + galleryItems.length) % galleryItems.length;
        showModal(currentIndex);
    }

    galleryItems.forEach((item, index) => {
        item.addEventListener('click', () => showModal(index));
    });

    closeBtn.addEventListener('click', closeModal);
    nextBtn.addEventListener('click', showNext);
    prevBtn.addEventListener('click', showPrev);
    
    modal.addEventListener('click', (e) => {
        if (e.target === modal) closeModal();
    });
});
