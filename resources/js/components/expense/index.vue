<template>
	<div class="row justify-content-center">
		<div class="col-xl-12 col-lg-12 col-md-12">
			<div class="row">
				<div class="col-lg-12 mb-4">
					<!-- Simple Tables -->
					<div class="card">
						<div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
							<h2 class="m-0 font-weight-bold text-primary">Category List</h2>
							<input type="text" placeholder="Search By Phone" v-model="searchTerm" class="form-control" style="width: 300px;margin-right: -900px;">
							<router-link to="/store-category" class="btn btn-primary float-right" style="margin-top: 6px;margin-right: 6px;">Add Category</router-link>
						</div>
						<div class="table-responsive">
							<table class="table align-items-center table-flush">
								<thead class="thead-light">
									<tr>
										<th>Category Name</th>
										<th>Action</th>
									</tr>
								</thead>
								<tbody>
									<tr v-for="category in filtersearch" :key="category.id">
										<td>{{ category.category_name }}</td>
										<td>
											<router-link :to="{name: 'editCategory', params: {id: category.id}}" class="btn btn-sm btn-primary">Edit</router-link>
											<a @click="deleteCategory(category.id)" class="btn btn-sm btn-danger" style="color: white">Delete</a>
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
			categories: [],
			searchTerm:""
		}
	},
	computed: {
		filtersearch(){
			return this.categories.filter(category => {
				return category.category_name.match(this.searchTerm)
			})
		}
	},
	methods: {
		allCategory(){
			axios.get('/api/category')
			.then(({data}) => (this.categories = data))
			.catch()
		},
		deleteCategory(id){
			Swal.fire({
				title: 'Are you sure?',
				text: "You won't be able to revert this!",
				icon: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: 'Yes, delete it!'
			}).then((result) => {
				if (result.isConfirmed) {
					axios.delete('/api/category/' + id)
						 .then(() => {
						 	this.categories = this.categories.filter(category => {
						 		return category.id != id
						 	})
						 })
						 .catch(() => {
						 	this.$router.push({name: 'category'})
						 })

					Swal.fire(
						'Deleted!',
						'Your file has been deleted.',
						'success'
						)
				}
			})
		}
	},
	mounted(){
		this.allCategory();
	}
}
</script>

<style lang="css" scoped>
#img_size{
	width: 40px;
}
</style>