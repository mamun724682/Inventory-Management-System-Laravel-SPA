<template>
	<div>
		<div class="d-sm-flex align-items-center justify-content-between mb-4">
			<h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
			<ol class="breadcrumb">
				<li class="breadcrumb-item"><a href="./">Home</a></li>
				<li class="breadcrumb-item active" aria-current="page">Dashboard</li>
			</ol>
		</div>

		<div class="row mb-3">
			<!-- Earnings (Monthly) Card Example -->
			<div class="col-xl-3 col-md-6 mb-4">
				<div class="card h-100">
					<div class="card-body">
						<div class="row align-items-center">
							<div class="col mr-2">
								<div class="text-xs font-weight-bold text-uppercase mb-1">Today Sell Amount</div>
								<div class="h5 mb-0 font-weight-bold text-gray-800">${{ todaySells }}</div>
								<div class="mt-2 mb-0 text-muted text-xs">
									<span class="text-success mr-2"><i class="fa fa-arrow-up"></i> 3.48%</span>
									<span>Since last month</span>
								</div>
							</div>
							<div class="col-auto">
								<i class="fas fa-calendar fa-2x text-primary"></i>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- Earnings (Annual) Card Example -->
			<div class="col-xl-3 col-md-6 mb-4">
				<div class="card h-100">
					<div class="card-body">
						<div class="row no-gutters align-items-center">
							<div class="col mr-2">
								<div class="text-xs font-weight-bold text-uppercase mb-1">Today Income</div>
								<div class="h5 mb-0 font-weight-bold text-gray-800">${{ income }}</div>
								<div class="mt-2 mb-0 text-muted text-xs">
									<span class="text-success mr-2"><i class="fas fa-arrow-up"></i> 12%</span>
									<span>Since last years</span>
								</div>
							</div>
							<div class="col-auto">
								<i class="fas fa-shopping-cart fa-2x text-success"></i>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- New User Card Example -->
			<div class="col-xl-3 col-md-6 mb-4">
				<div class="card h-100">
					<div class="card-body">
						<div class="row no-gutters align-items-center">
							<div class="col mr-2">
								<div class="text-xs font-weight-bold text-uppercase mb-1">Today Due</div>
								<div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">${{ due }}</div>
								<div class="mt-2 mb-0 text-muted text-xs">
									<span class="text-success mr-2"><i class="fas fa-arrow-up"></i> 20.4%</span>
									<span>Since last month</span>
								</div>
							</div>
							<div class="col-auto">
								<i class="fas fa-users fa-2x text-info"></i>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- Pending Requests Card Example -->
			<div class="col-xl-3 col-md-6 mb-4">
				<div class="card h-100">
					<div class="card-body">
						<div class="row no-gutters align-items-center">
							<div class="col mr-2">
								<div class="text-xs font-weight-bold text-uppercase mb-1">Expense Amount</div>
								<div class="h5 mb-0 font-weight-bold text-gray-800">${{ expense }}</div>
								<div class="mt-2 mb-0 text-muted text-xs">
									<span class="text-danger mr-2"><i class="fas fa-arrow-down"></i> 1.10%</span>
									<span>Since yesterday</span>
								</div>
							</div>
							<div class="col-auto">
								<i class="fas fa-comments fa-2x text-warning"></i>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
				<div class="col-lg-12 mb-4">
					<!-- Simple Tables -->
					<div class="card">
						<div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
							<h2 class="m-0 font-weight-bold text-primary">Stock Out Products</h2>
							<router-link to="/store-product" class="btn btn-primary float-right" style="margin-top: 6px;margin-right: 6px;">Add Product</router-link>
						</div>
						<div class="table-responsive">
							<table class="table align-items-center table-flush">
								<thead class="thead-light">
									<tr>
										<th>Product Name</th>
										<th>Product Code</th>
										<th>Image</th>
										<th>Buying Price</th>
										<th>Status</th>
										<th>Quantity</th>
										<th>Action</th>
									</tr>
								</thead>
								<tbody>
									<tr v-for="product in stockOutProducts" :key="product.id">
										<td>{{ product.product_name }}</td>
										<td>{{ product.product_code }}</td>
										<td><img :src="product.image" id="img_size"></td>
										<td>{{ product.buying_price }}</td>
										<td v-if="product.product_quantity >= 1"><span class="badge badge-success">Available</span></td>
										<td v-else=""><span class="badge badge-danger">Stock Out</span></td>
										<td>{{ product.product_quantity }}</td>
										<td>
											<router-link :to="{name: 'editStock', params: {id: product.id}}" class="btn btn-sm btn-primary">Edit Stock</router-link>
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
</template>

<script>
export default {

	name: 'home',

	created(){
		if (!User.loggedIn()) {
			this.$router.push({name: '/'})
		}
	},

	data () {
		return {
			todaySells: '',
			income: '',
			due: '',
			expense: '',
			stockOutProducts: '',
		}
	},

	mounted(){
		this.todaySell();
		this.todayIncome();
		this.todayDue();
		this.expenses();
		this.stockOutProduct();
	},

	methods: {
		todaySell(){
			axios.get('/api/today/sell')
			.then(({data}) => (this.todaySells = data))
			.catch()
		},
		todayIncome(){
			axios.get('/api/today/income')
			.then(({data}) => (this.income = data))
			.catch()
		},
		todayDue(){
			axios.get('/api/today/due')
			.then(({data}) => (this.due = data))
			.catch()
		},
		expenses(){
			axios.get('/api/total/expense')
			.then(({data}) => (this.expense = data))
			.catch()
		},
		stockOutProduct(){
			axios.get('/api/stockout/product')
			.then(({data}) => (this.stockOutProducts = data))
			.catch()
		},
	}
}
</script>

<style lang="css" scoped>
#img_size{
	width: 40px;
}
</style>