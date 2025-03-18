let url = 'http://www.jjj-food-chain-v3-small.com';
if(process.env.NODE_ENV != 'development'){
	url = '/api';
}
export default {
	url
}
