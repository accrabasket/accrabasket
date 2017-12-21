var app = angular.module('order', ['ui.bootstrap']);
app.controller('orderController', function ($scope, $http) {
    $scope.errorShow = false;
    $scope.showAttr = false;
    $scope.productData = {};
    $scope.filter = {};
    $scope.index = 0;
    $scope.filter.order_status = 'current_order';
    
    $scope.indexVal = [];
    $scope.errorStatus = false;
    $scope.errorMsg = '';
    $scope.selected_filter_level = 'Action';
    $scope.setFilterType = function(id){
        $scope.filter.filter_type = id;
        $scope.selected_filter_level = id.replace("_", " ");
    }
    
    $scope.selectPage = function(page_number) {
        $scope.filter.page = page_number;
        $scope.getOrderList();
    };
    
    $scope.currentPage = 1;
    $scope.numberOfRecord = 0;
    
    $scope.querySearch = function(){
        if($scope.selected_filter_level == 'Action'){
           $scope.errorStatus = true;
           $scope.errorMsg = 'Please select a action '; 
        }
        
        if($scope.filterText == '' || $scope.filterText == undefined){
           $scope.errorStatus = true;
           $scope.errorMsg = 'Please enter '+$scope.selected_filter_level; 
        }
        if(!$scope.errorStatus){
            $scope.filter.value = $scope.filterText;
            delete $scope.filter.page;
            $scope.getOrderList();
        }else{
            $timeout(function () {
                $scope.errorStatus = false;
                $scope.errorMsg = '';
            }, 2000);
        }
        
    }
    
    $scope.refresh = function() {
        $scope.filter = {};
        $scope.filter.page = 1;
        $scope.selected_filter_level = 'Action';
        $scope.filterText = '';
        $scope.getOrderList();
    }
    
    $scope.getOrderList = function() { 
        
        $http({
            method: 'POST',
            url: serverAdminApp + 'product/getOrderList',
            data : ObjecttoParams($scope.filter),
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        }).success(function (response) {
            $scope.orderList = {};
            if(response.status == 'success'){
                $scope.orderList = response.data;
                $scope.shipping_address_list = response.shipping_address_list;
                $scope.user_details = response.user_details;
                $scope.numberOfRecord = response.totalNumberOfOrder;
            }
        });
    }    
    
    $scope.shortUsingStatus = function(status){
        $scope.filter.order_status = status;
        $scope.getOrderList();
    }
    
	
});	


app.filter('underscoreless', function () {
  return function (input) {
      return input.replace(/_/g, ' ');
  };
});

app.filter('lengths', function () {
  return function (input) {
      return Object.keys(input).length;
  };
});