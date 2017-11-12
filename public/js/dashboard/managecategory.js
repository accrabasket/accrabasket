var app = angular.module('app', []);
app.controller('managecategory', function ($scope, $http, $sce,$timeout, categoryData) {
    $scope.errorShow = false;
    
    $scope.categoryData = {};
    $scope.id = '';
    function getCategory() {
        $http({
            method: 'POST',
            url: serverAdminApp + 'dashboard/getCategoryList',
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
    
    console.log(categoryData);
    if(categoryData != ''){
        var categoryData = jQuery.parseJSON(categoryData);
        $scope.categoryData.category_name = categoryData.category_name;
        $scope.categoryData.category_des = categoryData.category_des;
        $scope.categoryData.parent_category_id = categoryData.parent_category_id;
        $scope.id = categoryData.id;
    }
    
    getCategory();
    
    $scope.savecategory = function (categoryData) {
		var error = ' ';
		if(categoryData.category_name == undefined || categoryData.category_name == ''){
			error = 'Please enter Category name' ;
		}
		if($scope.id != ''){
			$scope.categoryData.id = $scope.id;
		}      
                
		if(error == ' '){       
			$http({
				method: 'POST',
				data : ObjecttoParams(categoryData),
				url: serverAdminApp + 'dashboard/savecategory',
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