export default class Modal{

    cancelBtn = document.getElementById('cancel')
    addBtn = document.getElementById('save')
    deleteBtn = document.getElementById('delete')
    modalRoot = document.getElementById('modal')
    constructor(){
        this.cancelBtn.addEventListener('click',this.open)
        this.addBtn.addEventListener('click',this.open)
        this.modalRoot.addEventListener('click',this.close)

        this.deleteBtn.addEventListener('click',this.open)
    }
    open = (e)=>{
        this.modalRoot.toggleAttribute('active')
    }
    close = (e)=>{
        if (e.target.id == 'modal') {
            this.modalRoot.toggleAttribute('active')
        }
    }
}
