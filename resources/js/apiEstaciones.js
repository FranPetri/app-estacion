async function getEstaciones(){
	const response = await fetch('https://mattprofe.com.ar/proyectos/app-estacion/datos.php?mode=list-stations');
	const data = await response.json();
	return data
}

getEstaciones().then(estaciones => {
	estaciones.forEach(estacion => {
		cardEstacion(estacion)
	});
})

function cardEstacion(info) {
	console.log(info)
	const tpl = tpl_estacion.content
	const clon = tpl.cloneNode(true);

	clon.querySelector('.card_estacion').setAttribute('href','detalle/'+info.chipid)

	clon.querySelector('.name_estacion').innerHTML = info.apodo
	clon.querySelector('.ubi_estacion').innerHTML = info.ubicacion
	clon.querySelector('.visitas_estacion').innerHTML = 'Visitas:' + info.visitas
	
	estaciones.appendChild(clon);
}