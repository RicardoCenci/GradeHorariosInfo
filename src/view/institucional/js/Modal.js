export default class Modal{

    cancelBtn = document.getElementById('cancel')
    addBtn = document.getElementById('save')
    deleteBtn = document.getElementById('delete')
    modalRoot = document.getElementById('modal')

    entranceInput = document.getElementById('entranceTime')
    exitInput = document.getElementById('exitTime')

    confirmModalBackButton = document.getElementById('cancelConfirm')
    contentRoot = document.querySelector('.modalContent')
    constructor(){
        this.modalRoot.addEventListener('click',this.close)
        this.addBtn.addEventListener('click',this.saveAction)
        this.deleteBtn.addEventListener('click',this.deleteAction)

        this.confirmModalBackButton.addEventListener('click',this.openConfirm)

    }
    open = (e)=>{
        const elementDataset = e.currentTarget.dataset
        this.entranceInput.value = elementDataset.entrance
        this.exitInput.value = elementDataset.exit
        this.modalRoot.toggleAttribute('active')
    }
    close = (e)=>{
        if (e.target.id == 'modal' || e.target.id == 'cancel') {
            this.modalRoot.toggleAttribute('active')
        }
    }
    deleteAction = (e)=>{
        this.openConfirm();
    }
    openConfirm = (e)=>{
        this.contentRoot.toggleAttribute('active')
    }
}
