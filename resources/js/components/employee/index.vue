<template>
	<div class="row justify-content-center">
		<div class="col-xl-12 col-lg-12 col-md-12">
			<div class="row">
				<div class="col-lg-12 mb-4">
					<!-- Simple Tables -->
					<div class="card">
						<div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
							<h2 class="m-0 font-weight-bold text-primary">Employee List</h2>
							<input type="text" placeholder="Search By Phone" v-model="searchTerm" class="form-control" style="width: 300px;margin-right: -900px;">
							<router-link to="/store-employee" class="btn btn-primary float-right" style="margin-top: 6px;margin-right: 6px;">Add Employee</router-link>
						</div>
						<div class="table-responsive">
							<table class="table align-items-center table-flush">
								<thead class="thead-light">
									<tr>
										<th>Name</th>
										<th>Photo</th>
										<th>Phone</th>
										<th>Salary</th>
										<th>Joining Date</th>
										<th>Action</th>
									</tr>
								</thead>
								<tbody>
									<tr v-for="employee in filtersearch" :key="employee.id">
										<td>{{ employee.name }}</td>
										<td><img :src="employee.photo" id="img_size"></td>
										<td>{{ employee.phone }}</td>
										<td>{{ employee.salary }}</td>
										<td>{{ employee.joining_date }}</td>
										<td>
											<a href="#" class="btn btn-sm btn-primary">Edit</a>
											<a href="#" class="btn btn-sm btn-danger">Delete</a>
										</td>
									</tr>
								</tbody>
							</table>
						</div>
						<div class="card-footer"></div>
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
			employees: [],
			searchTerm:""
		}
	},
	computed: {
		filtersearch(){
			return this.employees.filter(employee => {
				return employee.phone.match(this.searchTerm)
			})
		}
	},
	methods: {
		allEmployee(){
			axios.get('/api/employee')
				 .then(({data}) => (this.employees = data))
				 .catch()
		}
	},
	mounted(){
		this.allEmployee();
	}
}
</script>

<style lang="css" scoped>
#img_size{
	width: 40px;
}
</style>