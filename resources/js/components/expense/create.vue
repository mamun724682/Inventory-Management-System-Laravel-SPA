<template>
	<div class="row justify-content-center">
		<div class="col-xl-12 col-lg-12 col-md-12">
			<div class="card shadow-sm my-5">
				<div class="card-body p-0">
					<div class="row">
						<div class="col-lg-12">
							<router-link to="/expense" class="btn btn-primary float-right" style="margin-top: 6px;margin-right: 6px;">All Expense</router-link>
							<div class="login-form">
								<div class="text-center">
									<h1 class="h4 text-gray-900 mb-4">Add Expense</h1>
								</div>
								<form @submit.prevent='storeExpense'>
									<div class="form-group">
										<textarea type="text" class="form-control" id="exampleInputFirstName" placeholder="Enter Expense Details" v-model="form.details" rows="3"></textarea>
										<small class="text-danger" v-if="errors.details"> {{ errors.details[0] }} </small>
									</div>
									<div class="form-group">
										<input type="number" step="0.01" class="form-control" id="exampleInputFirstName" placeholder="Enter Expense Amount" v-model="form.amount">
										<small class="text-danger" v-if="errors.amount"> {{ errors.amount[0] }} </small>
									</div>
									<div class="form-group">
										<input type="date" class="form-control" id="exampleInputFirstName" placeholder="Enter Expense Date" v-model="form.expense_date">
										<small class="text-danger" v-if="errors.expense_date"> {{ errors.expense_date[0] }} </small>
									</div>
									
									<div class="form-group">
										<button type="submit" class="btn btn-primary btn-block">Submit</button>
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
				details: null,
				amount: null,
				expense_date: null,
			},
			errors: {}
		}
	},	
	methods:{
		storeExpense(){
			axios.post('/api/expense', this.form)
			.then(() => {
				this.$router.push({name: 'storeExpense'})
				Notification.success()
			})
			.catch(error => this.errors = error.response.data.errors)
		}
	}
}
</script>

<style lang="css" scoped>
</style>