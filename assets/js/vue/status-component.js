/** globals Vue **/

Vue.component( 'ef-status-indicator', {
	template: '<div class="ef-alert-wrap ef-hide"><p class="ef-alert ef-alert-success" v-if="show && success">{{message}}</p><p class="ef-alert ef-alert-warning" v-if="show && ! success">{{message}}</p></div>',
	props: [
		'success',
		'message',
		'show'
	],
	watch : {
		show: function () {
			if( this.show ){
				this.$el.className = "ef-alert-wrap ef-show";
			}else{
				this.$el.className = "ef-alert-wrap ef-hide";
			}
		}
	}
});

