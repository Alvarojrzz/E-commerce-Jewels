document.addEventListener("DOMContentLoaded", () => {
  const radios = document.querySelectorAll('input[name="metodo_pago"]');
  const divTarjeta = document.getElementById("datos-tarjeta");
  const divPaypal = document.getElementById("datos-paypal");
  const divTransf = document.getElementById("datos-transferencia");

  function ocultarTodos() {
    divTarjeta.style.display = "none";
    divPaypal.style.display = "none";
    divTransf.style.display = "none";
  }
  function mostrarSegmento(metodo) {
    ocultarTodos();
    if (metodo === "tarjeta") divTarjeta.style.display = "block";
    else if (metodo === "paypal") divPaypal.style.display = "block";
    else if (metodo === "transferencia") divTransf.style.display = "block";
  }
  radios.forEach((radio) => {
    radio.addEventListener("change", () => {
      mostrarSegmento(radio.value);
    });
  });
  const seleccionado = document.querySelector(
    'input[name="metodo_pago"]:checked'
  );
  if (seleccionado) {
    mostrarSegmento(seleccionado.value);
  }

  const radiosDir = document.querySelectorAll(
    'input[name="usar_direccion_id"]'
  );
  const bloqueNueva = document.getElementById("nueva-direccion-campos");

  const nuevosCampos = bloqueNueva
    ? bloqueNueva.querySelectorAll("input, select")
    : [];

  function actualizarVisibilidad() {
    if (radiosDir.length === 0) {
      bloqueNueva.style.display = "block";
      nuevosCampos.forEach((el) => {
        el.disabled = false;
        if (el.getAttribute("data-requerido") === "true") {
          el.required = true;
        }
      });
      return;
    }

    const elegido = Array.from(radiosDir).find((r) => r.checked);
    if (elegido && elegido.value === "nueva") {
      bloqueNueva.style.display = "block";
      nuevosCampos.forEach((el) => {
        el.disabled = false;
        if (el.getAttribute("data-requerido") === "true") {
          el.required = true;
        }
      });
    } else {
      bloqueNueva.style.display = "none";
      nuevosCampos.forEach((el) => {
        el.disabled = true;
        el.required = false;
      });
    }
  }

  radiosDir.forEach((radio) => {
    radio.addEventListener("change", actualizarVisibilidad);
  });
  actualizarVisibilidad();
});

document.addEventListener("DOMContentLoaded", () => {
  // Seleccionar elementos del DOM
  const radiosDir = document.querySelectorAll(
    'input[name="usar_direccion_id"]'
  );
  const bloqueNueva = document.getElementById("nueva-direccion-campos");
  const addressCards = document.querySelectorAll(".direccion-card");
  const paymentRadios = document.querySelectorAll('input[name="metodo_pago"]');
  const methodCards = document.querySelectorAll(".metodo-card");
  const cardDetails = document.getElementById("datos-tarjeta");
  const paypalDetails = document.getElementById("datos-paypal");
  const transferDetails = document.getElementById("datos-transferencia");

  // Obtener campos de la nueva dirección si existen
  const nuevosCampos = bloqueNueva
    ? bloqueNueva.querySelectorAll("input, select")
    : [];

  // Función para mostrar segmento de método de pago
  function mostrarSegmento(metodo) {
    // Ocultar todos los detalles
    cardDetails.style.display = "none";
    paypalDetails.style.display = "none";
    transferDetails.style.display = "none";

    // Mostrar el segmento seleccionado con animación
    if (metodo === "tarjeta") {
      cardDetails.style.display = "block";
      cardDetails.classList.add("fade-in");
    } else if (metodo === "paypal") {
      paypalDetails.style.display = "block";
      paypalDetails.classList.add("fade-in");
    } else if (metodo === "transferencia") {
      transferDetails.style.display = "block";
      transferDetails.classList.add("fade-in");
    }
  }

  // Función para actualizar visibilidad de la nueva dirección
  function actualizarVisibilidad() {
    if (radiosDir.length === 0) {
      // Si no hay radios de dirección, mostrar el bloque
      bloqueNueva.style.display = "block";
      nuevosCampos.forEach((el) => {
        el.disabled = false;
        if (el.getAttribute("data-requerido") === "true") {
          el.required = true;
        }
      });
      return;
    }

    const elegido = Array.from(radiosDir).find((r) => r.checked);
    if (elegido && elegido.value === "nueva") {
      // Mostrar campos de nueva dirección
      bloqueNueva.style.display = "block";
      nuevosCampos.forEach((el) => {
        el.disabled = false;
        if (el.getAttribute("data-requerido") === "true") {
          el.required = true;
        }
      });
    } else {
      // Ocultar campos de nueva dirección
      bloqueNueva.style.display = "none";
      nuevosCampos.forEach((el) => {
        el.disabled = true;
        el.required = false;
      });
    }
  }

  // Event listeners para radios de dirección
  radiosDir.forEach((radio) => {
    radio.addEventListener("change", () => {
      actualizarVisibilidad();

      // Actualizar clases de selección visual
      addressCards.forEach((card) => {
        const cardRadio = card.querySelector('input[type="radio"]');
        if (cardRadio && cardRadio.checked) {
          card.classList.add("selected");
        } else {
          card.classList.remove("selected");
        }
      });
    });
  });

  // Event listeners para tarjetas de dirección
  addressCards.forEach((card) => {
    card.addEventListener("click", function () {
      const radio = this.querySelector('input[type="radio"]');
      if (radio) {
        radio.checked = true;

        // Actualizar selección visual
        addressCards.forEach((c) => c.classList.remove("selected"));
        this.classList.add("selected");

        // Actualizar visibilidad
        actualizarVisibilidad();
      }
    });
  });

  // Event listeners para radios de método de pago
  paymentRadios.forEach((radio) => {
    radio.addEventListener("change", () => {
      mostrarSegmento(radio.value);

      // Actualizar clases de selección visual
      methodCards.forEach((card) => {
        const cardRadio = card.querySelector('input[type="radio"]');
        if (cardRadio && cardRadio.checked) {
          card.classList.add("selected");
        } else {
          card.classList.remove("selected");
        }
      });
    });
  });

  // Event listeners para tarjetas de método de pago
  methodCards.forEach((card) => {
    card.addEventListener("click", function () {
      const radio = this.querySelector('input[type="radio"]');
      if (radio) {
        radio.checked = true;

        // Actualizar selección visual
        methodCards.forEach((c) => c.classList.remove("selected"));
        this.classList.add("selected");

        // Mostrar el segmento correspondiente
        mostrarSegmento(radio.value);
      }
    });
  });

  // Inicializar estado de la interfaz
  actualizarVisibilidad();

  // Mostrar el método de pago seleccionado inicialmente
  const paymentSelected = document.querySelector(
    'input[name="metodo_pago"]:checked'
  );
  if (paymentSelected) {
    mostrarSegmento(paymentSelected.value);
  }

  // Configurar animación para el botón de confirmar
  const confirmBtn = document.querySelector(".btn-confirmar");
  confirmBtn.addEventListener("click", function (e) {
    e.preventDefault();
    this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Procesando...';

    // Simular procesamiento
    setTimeout(() => {
      this.innerHTML =
        '<i class="fas fa-check-circle"></i> ¡Pedido Confirmado!';
      this.style.backgroundColor = "var(--color-exito)";
    }, 1500);
  });
});
