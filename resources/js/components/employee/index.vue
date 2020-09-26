<template>
	<div class="row justify-content-center">
		<div class="col-xl-12 col-lg-12 col-md-12">
			<div class="card shadow-sm my-5">
				<div class="card-body p-0">
					<div class="row">
						<div class="col-lg-12">
							<router-link to="/store-employee" class="btn btn-primary float-right" style="margin-top: 6px;margin-right: 6px;">Add Employee</router-link>
							<div class="login-form">
								<div class="text-center">
									<h1 class="h4 text-gray-900 mb-4">All Employee</h1>
									
								</div>
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
				name: null,
				email: null,
				phone: null,
				salary: null,
				address: null,
				nid: null,
				joining_date: null,
				photo: null,
			},
			errors: {}
		}
	},
}
</script>

<style lang="css" scoped>
</style>