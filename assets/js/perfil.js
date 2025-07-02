document.addEventListener('DOMContentLoaded', function() {
    const navLinks = document.querySelectorAll('.nav-link');
    const sections = document.querySelectorAll('.profile-content > section');
    
    navLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            navLinks.forEach(l => l.classList.remove('active'));
            this.classList.add('active');
            sections.forEach(section => {
                section.style.display = 'none';
            });
            const targetId = this.getAttribute('href').substring(1);
            document.getElementById(targetId).style.display = 'block';
        });
    });
    document.getElementById('profile').style.display = 'block';
});

document.addEventListener('DOMContentLoaded', function() {
    const editButtons = document.querySelectorAll('.edit-address');
    const addressForm = document.getElementById('address-form');
    const formTitle = document.getElementById('form-title');
    const saveBtn = document.getElementById('save-btn');
    const cancelBtn = document.getElementById('cancel-btn');
    const addressId = document.getElementById('address-id');
    
    editButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            
            const id = this.dataset.id;
            const calle = this.dataset.calle;
            const numero = this.dataset.numero;
            const piso = this.dataset.piso;
            const cod_postal = this.dataset.cod_postal;
            const ciudad = this.dataset.ciudad;
            const provincia = this.dataset.provincia;
            const pais = this.dataset.pais;
            const principal = this.dataset.principal;

            document.getElementById('calle').value = calle;
            document.getElementById('numero').value = numero;
            document.getElementById('piso').value = piso;
            document.getElementById('cod_postal').value = cod_postal;
            document.getElementById('ciudad').value = ciudad;
            document.getElementById('provincia').value = provincia;
            document.getElementById('pais').value = pais;
            document.getElementById('direccion_principal').checked = (principal === '1');
            addressId.value = id;

            addressForm.action = 'edit_direc.php';
            formTitle.textContent = 'Editar dirección';
            saveBtn.textContent = 'Actualizar Dirección';
            document.getElementById('addresses').scrollIntoView({ behavior: 'smooth' });
        });
    });
    
    // Manejar el botón de cancelar
    cancelBtn.addEventListener('click', function() {
        addressForm.reset();
        addressId.value = '';
        addressForm.action = 'add_address.php';
        formTitle.textContent = 'Añadir nueva dirección';
        saveBtn.textContent = 'Guardar Dirección';
    });
});