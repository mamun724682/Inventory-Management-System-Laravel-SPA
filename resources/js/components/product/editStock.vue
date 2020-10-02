<template>
	<div class="row justify-content-center">
		<div class="col-xl-12 col-lg-12 col-md-12">
			<div class="card shadow-sm my-5">
				<div class="card-body p-0">
					<div class="row">
						<div class="col-lg-12">
							<router-link to="/stock" class="btn btn-primary float-right" style="margin-top: 6px;margin-right: 6px;">Go Back</router-link>
							<div class="login-form">
								<div class="text-center">
									<h1 class="h4 text-gray-900 mb-4">Edit Stock</h1>
								</div>
								<form @submit.prevent='updateStock'>
									<div class="form-group">
										<input type="number" class="form-control" id="exampleInputNid" placeholder="Enter Product Quantity" v-model='form.product_quantity'>
										<small class="text-danger" v-if="errors.product_quantity">{{ errors.product_quantity[0] }}</small>
									</div>
									
									<div class="form-group">
										<button type="submit" class="btn btn-primary btn-block">Update</button>
									</div>
								</form>
								<div class="text-center">
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
export default {

	created(){
		if (!User.loggedIn()) {
			this.$router.push({name: '/'})
		}
	},

	data () {
		return {
			form:{
				product_quantity: '',
			},
			errors: {},
		}
	},
	created(){
		let id = this.$route.params.id
		axios.get('/api/product/'+id)
		.then(({data}) => (this.form = data))
		.catch(console.log('error'))
	},
	methods:{
		updateStock(){
			let id = this.$route.params.id
			axios.patch('/api/stock/' + id, this.form)
			.then(() => {
				this.$router.push({name: 'stock'})
				Notification.success()
			})
			.catch(error => this.errors = error.response.data.errors)
		}
	}
}
</script>

<style lang="css" scoped>
</style>

