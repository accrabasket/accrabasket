var app = angular.module('app', []);
app.controller('managecategory', function ($scope, $http, $sce,$timeout, categoryData) {
    $scope.errorShow = false;
    
    $scope.categoryData = {};
    $scope.categoryData.parent_category_id = 0;
    $scope.id = '';
    function getCategory() {
        var filter = {};
        filter.categoryHavingNoProduct = 1;
        $http({
            method: 'POST',
            url: serverAdminApp + 'dashboard/getPromotionList',
            data : ObjecttoParams(filter),
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        }).success(function (response) {
            $scope.categoryList = {};
            if(response.status == 'success'){
                $scope.categoryList = response.data;
                if($scope.id != ''){
                   delete($scope.categoryList[$scope.id]); 
                }
            }
        });
    }
    
    if(categoryData != ''){
        var categoryData = jQuery.parseJSON(categoryData);
        $scope.categoryData.promotion_name = categoryData.promotion_name;
        $scope.categoryData.type = categoryData.type;
        $scope.categoryData.value = categoryData.value;
        $scope.categoryData.promotion_sequence = categoryData.promotion_sequence;
        $scope.id = categoryData.id;
    }
    
    getCategory();
    
    $scope.savepromotion = function (categoryData) {
		var error = ' ';
		if(categoryData.promotion_name == undefined || categoryData.promotion_name == ''){
			error = 'Please enter Category name' ;
		}
		if($scope.id != ''){
			$scope.categoryData.id = $scope.id;
		}
                
                if($('#cat_img').val() != ''){
			$scope.categoryData.image = $('#cat_img').val();
		}
                
		if(error == ' '){       
			$http({
				method: 'POST',
				data : ObjecttoParams(categoryData),
				url: serverAdminApp + 'dashboard/savepromotion',
				headers: {'Content-Type': 'application/x-www-form-urlencoded'},
			}).success(function (response) {
				if (response.status == 'success') {
                                        $scope.successShow = true;
                                        $scope.successMsg = response.msg ;
                                        $scope.successShow = false;
                                        var path = serverAdminApp + 'dashboard/managecategory';
                                        window.location.href = path;
				}else{
                                    $scope.errorShow = true;
                                    $scope.errorMsg = response.msg == undefined ? 'somthing went wrong ':response.msg;
                                    $timeout(function(){
                                            $scope.errorShow = false;
                                    },2000);
				}
			});
		}else{
			$scope.errorShow = true;
			$scope.errorMsg = error;
			$timeout(function(){
				$scope.errorShow = false;
			},2000)
		}
		
    };
    
    
});


 function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            $('#cat_img').val(e.target.result);
        }
        reader.readAsDataURL(input.files[0]);
    }
}
function uploadImageInTemp(obj) {
    readURL(obj);
}
;
