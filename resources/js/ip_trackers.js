async function chargeTracker(ip){
	const response = await fetch('api/trackers.php?ip='+ip+'&modo=chargeTracker');
	const data = await response.json();
	console.log(data)
	return data
}

let ip = document.querySelector('.client').textContent

chargeTracker(ip).then(cliente => {
	console.log(cliente)
})