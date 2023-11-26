const map = L.map('map').setView([-27.4692131, -58.8306349], 2);

const tiles = L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
	maxZoom: 19
}).addTo(map);

/**
 * 
 * Función asincrona para acceder al listado que tiene las latitudes
 * y longitudes a pintar como marcadores en el mapa
 * 
 * */
async function loadTracker(){
	const response = await fetch("api/trackers.php?modo=chargeMap");
	const data = await response.json();
	console.log(data)
	return data;
}

/* Obtenemos el listado de datos */
loadTracker().then( info => {
	console.log(info)
	/* Recorremos la lista por fila */
	info.forEach( fila => {
		console.log(fila)
		/* Recuperamos la información necesaria para colocar los marcadores */
		let latitud = fila["latitud"];
		let longitud = fila["longitud"];
		let accesos = fila["accesos"];

		/* Genera un marcador con un popup dentro del mapa*/
		const marker = L.marker([latitud, longitud]).addTo(map)
		.bindPopup('Accesos: '+accesos)
		.openPopup();
	})
})
