const imagenesAlternas = [
	"img/servicio_exterminacion.jpg",
	"img/servicio_fumigacion.jpg",
	"img/servicio_desinfeccion.jpg",
];
let idxImg = 0;

function alternarImagen() {
	idxImg = (idxImg + 1) % imagenesAlternas.length;
	const imgElem = document.getElementById("imagenPrincipal");
	if (imgElem) imgElem.src = imagenesAlternas[idxImg];
}

let grande = false;
function cambiarTamParrafo() {
	const p = document.getElementById("descripcion-hero");
	if (!p) return;
	if (!grande) {
		p.style.fontSize = "20px";
		p.textContent =
			"En ControlMax, entendemos que las plagas son más que una simple molestia, son una amenaza para tu salud, tu propiedad y tu tranquilidad. Por eso, nos dedicamos a ofrecer soluciones de control de plagas profesionales, eficaces y seguras para hogares y negocios";
	} else {
		p.style.fontSize = "16px";
		p.textContent =
			"Protegemos tu hogar y negocio con tratamientos seguros y efectivos.";
	}
	grande = !grande;
}

function inicializarUI() {
	const yearElem = document.getElementById("year");
	if (yearElem) yearElem.textContent = new Date().getFullYear();
}

document.addEventListener("DOMContentLoaded", inicializarUI);

window.alternarImagen = alternarImagen;
window.cambiarTamParrafo = cambiarTamParrafo;
