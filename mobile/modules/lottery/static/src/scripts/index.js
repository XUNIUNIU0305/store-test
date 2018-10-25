import '../styles/index.scss';

// mask evt binding
document.addEventListener('touchend', e => {
    const tar = e.target;
    const mask = document.querySelector('.mask-bg');
    const maskCnts = document.querySelectorAll('.mask-cnt');

    // show mask
    if (tar.hasAttribute('data-mask')) {
        e.preventDefault();
        e.stopPropagation();
        let hrefId = tar.getAttribute('href');
        [].forEach.call(
            maskCnts,
            (cnt) => {
                cnt.classList.remove('show');
            }
        )
        document.querySelector(hrefId).classList.add('show');
        mask.classList.remove('hidden');
    }

    // hide mask
    if (tar.hasAttribute('data-dismiss')) {
        e.preventDefault();
        e.stopPropagation();
        mask.classList.add('hidden');
    }
}, false)