<template>
</template>

<script>
export default {

	created(){
		localStorage.removeItem('token')
		localStorage.removeItem('user')

		Toast.fire({
			icon: 'success',
			title: 'Logout in successfully'
		})

		this.$router.push({name: '/'})
	}
}
</script>

<style lang="css" scoped>
</style>