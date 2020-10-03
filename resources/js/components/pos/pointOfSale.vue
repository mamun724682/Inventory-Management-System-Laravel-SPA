<template>
	<div>
		<div class="container-fluid" id="container-wrapper">
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="./">Home</a></li>
              <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
            </ol>
          </div>

          <div class="row mb-3">

            <!-- Area Chart -->
            <div class="col-xl-4 col-lg-5">
              <div class="card mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">Monthly Recap Report</h6>

                </div>
                <div class="card-body">
                  <div class="chart-area"><div class="chartjs-size-monitor"><div class="chartjs-size-monitor-expand"><div class=""></div></div><div class="chartjs-size-monitor-shrink"><div class=""></div></div></div>



                  </div>
                </div>
              </div>
            </div>

            <!-- Pie Chart -->
            <div class="col-xl-8 col-lg-7">
              <div class="card mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">Products Sold</h6>
                  
                  <input type="text" placeholder="Search" v-model="searchTerm" class="form-control" style="width: 300px;">
                </div>
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                	<li class="nav-item" role="presentation">
                		<a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Home</a>
                	</li>

                	<li class="nav-item" role="presentation" v-for="category in categories" :key="category.id">
                		<a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false" @click="categoryProduct(category.id)">{{ category.category_name }}</a>
                	</li>

                </ul>
                <div class="tab-content" id="myTabContent">
                	<div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                		<div class="card-body">
                			<div class="row">
                				<div class="col-lg-3 col-md-3 col-sm-6 col-6" v-for="product in filtersearch" :key="product.id">
                					<div class="card" style="align-items: center; margin-bottom: 10px">
                						<img :src="product.image" class="card-img-top" id="image_size" alt="...">
                						<div class="card-body">
                							<h5 class="card-title text-center">{{ product.product_name }}</h5>
                							<td v-if="product.product_quantity >= 1"><span class="badge badge-success">Available <span class="badge badge-light">{{ product.product_quantity }}</span></span></td>
                							<td v-else=""><span class="badge badge-danger">Stock Out</span></td>
                							<!-- <a href="#" class="btn btn-primary">Go somewhere</a> -->
                						</div>
                					</div>
                				</div>
                			</div>
                		</div>
                	</div>
                	<div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                		<div class="card-body">
                			<div class="row">
                				<div class="col-lg-3 col-md-3 col-sm-6 col-6" v-for="catProduct in filterCatSearch" :key="catProduct.id">
                					<div class="card" style="align-items: center; margin-bottom: 10px">
                						<img :src="catProduct.image" class="card-img-top" id="image_size" alt="...">
                						<div class="card-body">
                							<h5 class="card-title text-center">{{ catProduct.product_name }}</h5>
                							<td v-if="catProduct.product_quantity >= 1"><span class="badge badge-success">Available <span class="badge badge-light">{{ catProduct.product_quantity }}</span></span></td>
                							<td v-else=""><span class="badge badge-danger">Stock Out</span></td>
                							<!-- <a href="#" class="btn btn-primary">Go somewhere</a> -->
                						</div>
                					</div>
                				</div>
                			</div>
                		</div>
                	</div>
                </div>
              </div>
            </div>
          </div>
          <!--Row-->

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

	mounted(){
		this.allProduct();
		this.allCategory();
	},

	data () {
		return {
			products: [],
			categories: [],
			categoryProducts: [],
			searchTerm:""
		}
	},

	computed: {
		filtersearch(){
			return this.products.filter(product => {
				return product.product_name.match(this.searchTerm)
			})
		},
		filterCatSearch(){
			return this.categoryProducts.filter(catProduct => {
				return catProduct.product_name.match(this.searchTerm)
			})
		}
	},

	methods: {
		allProduct(){
			axios.get('/api/product')
			.then(({data}) => (this.products = data))
			.catch()
		},
		allCategory(){
			axios.get('/api/category')
			.then(({data}) => (this.categories = data))
			.catch()
		},
		categoryProduct(id){
			axios.get('/api/category/product/' + id)
			.then(({data}) => (this.categoryProducts = data))
			.catch()
		}
	}
}
</script>

<style lang="css" scoped>
#image_size{
	height: 100px;
	width: 135px;
}
</style>