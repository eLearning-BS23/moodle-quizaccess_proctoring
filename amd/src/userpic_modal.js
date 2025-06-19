import ModalFactory from 'core/modal_factory';

export const init = () => {
    document.querySelectorAll('.userpic-modal-trigger').forEach(el => {
        el.addEventListener('click', async (e) => {
            const imgsrc = el.getAttribute('data-imgsrc');
            const userfullname = el.getAttribute('data-userfullname');

            const body = `<div style="text-align: center;"><img src="${imgsrc}" alt="${userfullname}" style="width: 200px; height: auto;"></div>`;

            ModalFactory.create({
                title: userfullname,
                body: body,
                type: ModalFactory.types.DEFAULT
            }).then(modal => {
                modal.show();
            });
        });
    });
};
