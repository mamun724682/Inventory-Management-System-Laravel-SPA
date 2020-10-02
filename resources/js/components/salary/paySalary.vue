<template>
	<div class="row justify-content-center">
		<div class="col-xl-12 col-lg-12 col-md-12">
			<div class="card shadow-sm my-5">
				<div class="card-body p-0">
					<div class="row">
						<div class="col-lg-12">
							<router-link to="/given-salary" class="btn btn-primary float-right" style="margin-top: 6px;margin-right: 6px;">Add Salary</router-link>
							<div class="login-form">
								<div class="text-center">
									<h1 class="h4 text-gray-900 mb-4">Pay Salary</h1>
								</div>
								<form @submit.prevent='paidSalary'>
									<div class="form-group">
										<div class="form-row">
											<div class="col-md-6">
												<label for="exampleFormControlSelect1">Name</label>
												<input disabled type="text" class="form-control" id="exampleInputFirstName" placeholder="Name" v-model='form.name'>
											</div>
											<div class="col-md-6">
												<label for="exampleFormControlSelect1">Email</label>
												<input disabled type="email" class="form-control" id="exampleInputEmail" placeholder="Email" v-model='form.email'>
											</div>
										</div>
									</div>
									<div class="form-group">
										<div class="form-row">
											<div class="col-md-6">
												<label for="exampleFormControlSelect1">Salary</label>
												<input disabled type="number" class="form-control" id="exampleInputSalary" placeholder="Enter Salary" v-model='form.salary'>
												<small class="text-danger" v-if="errors.salary">{{ errors.salary[0] }}</small>
											</div>
											<div class="col-md-6">
												<label for="exampleFormControlSelect1">Select Month</label>
												<select class="form-control" id="exampleFormControlSelect1" v-model="form.salary_month">
													<option value="January">January</option>
													<option value="February">February</option>
													<option value="March">March</option>
													<option value="April">April</option>
													<option value="May">May</option>
													<option value="June">June</option>
													<option value="July">July</option>
													<option value="August">August</option>
													<option value="September">September</option>
													<option value="October">October</option>
													<option value="November">November</option>
													<option value="December">December</option>
												</select>
												<small class="text-danger" v-if="errors.salary_month"> {{ errors.salary_month[0] }} </small>
											</div>
										</div>
									</div>
									
									<div class="form-group">
										<button type="submit" class="btn btn-primary btn-block">Pay Now</button>
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
				salary_month: '',
				salary: ''
			},
			errors: {}
		}
	},
	mounted(){
		let id = this.$route.params.id
		axios.get('/api/employee/' + id)
			 .then(({data}) => (this.form = data))
			 .catch(console.log('error'))
	},
	methods:{
		paidSalary(){
			let id = this.$route.params.id
			axios.post('/api/salary/paid/'+id,this.form)
				 .then(() => {
				this.$router.push({ name: 'given-salary'})
				Notification.success()
			})
			.catch(error =>this.errors = error.response.data.errors)
		},
	}
}
</script>

<style lang="css" scoped>
</style>