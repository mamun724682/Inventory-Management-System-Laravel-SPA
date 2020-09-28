<template>
	<div class="row justify-content-center">
		<div class="col-xl-12 col-lg-12 col-md-12">
			<div class="row">
				<div class="col-lg-12 mb-4">
					<!-- Simple Tables -->
					<div class="card">
						<div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
							<h2 class="m-0 font-weight-bold text-primary">Employee List</h2>
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
									<tr>
										<td><a href="#">RA0449</a></td>
										<td>Udin Wayang</td>
										<td>Nasi Padang</td>
										<td><span class="badge badge-success">Delivered</span></td>
										<td><a href="#" class="btn btn-sm btn-primary">Detail</a></td>
										<td><a href="#" class="btn btn-sm btn-primary">Detail</a></td>
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
	}
}
</script>

<style lang="css" scoped>
</style>