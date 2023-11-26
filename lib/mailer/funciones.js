document.addEventListener("DOMContentLoaded", () => {
	
	if (document.querySelector('#btn-Login') !== null) { 
		document.querySelector("#btn-Login").addEventListener("click", element => {

			element.preventDefault()

			let destinatario = document.querySelector("#email_user").value
			let password = document.querySelector("#pass_user").value
			let station = document.querySelector('#station_id').value

			getProfile(destinatario,password,'login').then(usuarios => {
				console.log(usuarios)
				if (usuarios['errno'] == 401) {
					let token = usuarios[0]['token'];
					
					let motivo = 'Inicio de Sesión'

					let navegador = usuarios[0][0]
					let ip = usuarios[0][1]
					let so = usuarios[0][2]

					let contenido = '<p>Ip: '+ip+'</p><p>Sistema Operativo: '+so+'</p><p>Navegador: '+navegador+'</p><a href="https://mattprofe.com.ar/alumno/3900/app-estacion/blocked/'+token+'">No fui yo, bloquear cuenta</a>'	
					
					sendMail(destinatario,'Intento de Acceso', contenido).then( resultado => {
						console.log(resultado)
					})
					document.querySelector('.alert').textContent = 'Credenciales no validas'
					return false
				}		

				if (usuarios['errno'] == 402) {
					document.querySelector('.alert').textContent = usuarios['error']
					return false	
				}
				if (usuarios['errno'] == 403) {
					document.querySelector('.alert').textContent = usuarios['error']
					return false
				}
				if (usuarios['errno'] == 404) {
					document.querySelector('.alert').textContent = usuarios['error']
					return false
				}
				if (usuarios['errno'] == 201) {
					window.location.href = 'http://mattprofe.com.ar/alumno/3900/app-estacion/admin'
					return true;
				}

				let token = usuarios[0]['token'];
					
				let motivo = 'Inicio de Sesión'

				let navegador = usuarios[0][0]
				let ip = usuarios[0][1]
				let so = usuarios[0][2]

				let contenido = '<p>Ip: '+ip+'</p><p>Sistema Operativo: '+so+'</p><p>Navegador: '+navegador+'</p><a href="https://mattprofe.com.ar/alumno/3900/app-estacion/blocked/'+token+'">No fui yo, bloquear cuenta</a>'	
				
				sendMail(destinatario, motivo, contenido).then( resultado => {
					console.log(resultado)
				})
				window.location.href = 'http://mattprofe.com.ar/alumno/3900/app-estacion/detalle/'+station
			})

		})
	}

	if (document.querySelector('#btn-register') !== null) {
		document.querySelector('#btn-register').addEventListener('click', element => {
			element.preventDefault()

		 	let email = document.querySelector('#email_user').value
		 	let pass = document.querySelector('#pass_user').value
		 	let check_pass = document.querySelector('#check_pass_user').value

		 	if (pass !== check_pass) {
		 		document.querySelector('.alert').textContent = 'Las contraseñas son diferentes';
		 		return false
		 	}
		 	
		 	getProfile(email,pass,'register').then(usuarios => {
		 		console.log(usuarios)
		 		
		 		if (usuarios['errno'] == 300) {
		 			document.querySelector('.alert').textContent = usuarios['error'] + '<a>Inicie Sesión</a>';
		 			return false
		 		}

		 		if (usuarios['errno'] == 200) {
		 			document.querySelector('.alert').textContent = usuarios['error'];
				
					let token = usuarios['token_action'];
					
					let motivo = 'Bienvenido a App-Estacion';

					let contenido = '<a href="https://mattprofe.com.ar/alumno/3900/app-estacion/validate/'+token+'">Click aqui para activar tu usuario</a>'	
					
					sendMail(email, motivo, contenido).then( resultado => {
						console.log(resultado)
					})
		 			window.location.href = 'http://mattprofe.com.ar/alumno/3900/app-estacion/login'
		 		}
		 	})

		 	
		})
	}

	if (document.querySelector('#validating') !== null) {
		let token = document.querySelector('.alert_maxed').textContent

		getProfileByToken(token,'validate').then(usuarios => {
			if (usuarios['errno'] == 404) {
				document.querySelector('.alert_maxed').textContent = usuarios['error'];
				return false
			}

			let email = usuarios[0]['email'];

			let motivo = 'Tu cuenta ya ha sido activada en App-Estacion';

			let contenido = 'Ya podras ver nuestras diferentes estaciones!!'	
			
			sendMail(email, motivo, contenido).then( resultado => {
				console.log(resultado)
			})
			window.location.href = 'http://mattprofe.com.ar/alumno/3900/app-estacion/login';
		})
	}

	if (document.querySelector('#blocking') !== null) {
		let token = document.querySelector('.alert_maxed').textContent

		getProfileByToken(token,'blocked').then(usuarios => {
			console.log(usuarios)
			if (usuarios['errno'] == 404) {
				document.querySelector('.alert_maxed').textContent = usuarios['error'];
				return false
			}

			let email = usuarios[0]['email'];

			let motivo = 'Tu cuenta ha sido Bloqueada de App-Estacion';
			
			let contenido = '<a href="http://mattprofe.com.ar/alumno/3900/app-estacion/reset/'+usuarios['token_action']+'">Click aqui para cambiar contraseña</a>'	
			
			sendMail(email, motivo, contenido).then( resultado => {
				console.log(resultado)
			})

			document.querySelector('.alert_maxed').textContent = 'Su usuario ha sido bloqueado, revise su correo electronico';
		})
	}

	if (document.querySelector('#recovering') !== null) {
		document.querySelector('#btn-recover').addEventListener('click', element => {
			element.preventDefault();

			let email_user = document.querySelector('#email_user').value
			
			getProfileByEmail(email_user,'recover').then(usuarios => {
				if (usuarios['errno'] == 404) {
					document.querySelector('.alert_maxed').innerHTML = 'El email no se encuentra registrado<a href="http://mattprofe.com.ar/alumno/3900/app-estacion/register">Registrarse</a>';
					return false
				}

				let email = usuarios[0]['email'];

				let motivo = 'Ha comenzado la recuperacion de su cuenta en App-Estacion';
				
				let contenido = '<a href="http://mattprofe.com.ar/alumno/3900/app-estacion/reset/'+usuarios['token_action']+'">Click aqui para restablecer contraseña</a>'	
				
				sendMail(email, motivo, contenido).then( resultado => {
					console.log(resultado)
				})
			})
		})
	}

	if (document.querySelector('#reseting') !== null) {
		let token = document.querySelector('.alert_maxed').textContent
		getProfileByToken(token,'reset-test').then(usuarios => {
			if(usuarios['errno'] == 404){
				document.querySelector('.container_form').style.display = 'none'
				document.querySelector('.alert_maxed').style.display = 'block'
				document.querySelector('.alert_maxed').textContent = usuarios['error']
				return false
			}
		})

		document.querySelector("#btn-reset").addEventListener("click", element => {
			element.preventDefault();

			let token = document.querySelector('.token').textContent

			let pass = document.querySelector('#pass_user').value
			let check_pass = document.querySelector('#check_pass_user').value


			if (pass !== check_pass) {
				document.querySelector('.alert_maxed').textContent = 'Conrtaseñas diferentes'
				document.querySelector('.alert_maxed').style.display = 'block'
				return false
			}
			getProfileChangePass(token,pass,'reset').then(usuarios => {			
				document.querySelector('.alert_maxed').style.display = 'none'
				
				let email_user = usuarios[0]['email']

				let motivo = 'Han cambiado la contraseña de tu cuenta en App-Estacion'

				let navegador = usuarios[0][0]
				let ip = usuarios[0][1]
				let so = usuarios[0][2]

				let contenido = '<p>Ip: '+ip+'</p><p>Sistema Operativo: '+so+'</p><p>Navegador: '+navegador+'</p><a href="https://mattprofe.com.ar/alumno/3900/app-estacion/blocked/'+usuarios[0]['token']+'">No fui yo, bloquear cuenta</a>'	
				sendMail(email_user, motivo, contenido).then( resultado => {
					console.log(resultado)
				})
				window.location.href = 'http://mattprofe.com.ar/alumno/3900/app-estacion/login'
			})
		})
	}
})


async function getProfile(email,pass,type){
	const response = await fetch('api/usuarios.php?email='+email+'&pass='+pass+'&type='+type);
	const data = await response.json();
	return data;
}

async function getProfileByToken(token,type){
	const response = await fetch('../api/usuarios.php?token='+token+'&type='+type);
	const data = await response.json();
	return data;
}

async function getProfileChangePass(token,pass,type){
	const response = await fetch('../api/usuarios.php?token='+token+'&type='+type+'&pass='+pass);
	const data = await response.json();
	return data;
}


async function getProfileByEmail(email,type){
	const response = await fetch('api/usuarios.php?email='+email+'&type='+type);
	const data = await response.json();
	return data;	
}

// Función asíncrona para el envio de email
async function sendMail(destinatario, motivo, contenido){

	let form = new FormData()

	form.append("destinatario", destinatario)
	form.append("motivo", motivo)
	form.append("contenido", contenido)
	form.append("send", "mail")

	options = {method: 'POST',
				body: form}

	const response = await fetch("https://mattprofe.com.ar/alumno/3900/app-estacion/lib/mailer/sendmail.php", options)
	const data = await response.json()

	return data
}