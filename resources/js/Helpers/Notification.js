class Notification{

	success(){
		new Noty({
			type: 'success',
			layout: 'topRight',
			text: 'Successfully done!',
			timeout: 1000,
		}).show();
	}

	alert(){
		new Noty({
			type: 'alert',
			layout: 'topRight',
			text: 'Are you sure?',
			timeout: 1000,
		}).show();
	}

	warning(){
		new Noty({
			type: 'warning',
			layout: 'topRight',
			text: 'Oops wrong!',
			timeout: 1000,
		}).show();
	}

	error(){
		new Noty({
			type: 'error',
			layout: 'topRight',
			text: 'Something went wrong!',
			timeout: 1000,
		}).show();
	}

	image_validation(){
		new Noty({
			type: 'error',
			layout: 'topRight',
			text: 'File size should be less then 1MB!',
			timeout: 2000,
		}).show();
	}

	cart_success(){
		new Noty({
			type: 'success',
			layout: 'topRight',
			text: 'Successfully added to cart!',
			timeout: 2000,
		}).show();
	}

	cart_delete(){
		new Noty({
			type: 'error',
			layout: 'topRight',
			text: 'Product removed from cart!',
			timeout: 2000,
		}).show();
	}
}

export default Notification = new Notification()