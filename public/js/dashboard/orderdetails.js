var app = angular.module('order', []);
app.controller('orderDetailController', function ($scope, $http, $timeout) {
    $scope.order_item_ids = {};
   $scope.AddItemToMarkOutOfStock = function() {
       console.log($scope.order_item_ids)
        $scope.orderItemIdsToMarkOutOfStock = [];
        
        angular.forEach($scope.order_item_ids, function(value, key) {
            if(value) {
                $scope.orderItemIdsToMarkOutOfStock.push(key);
            }
        }); 
    };   
    
    $scope.markOrderItemOutOfStock = function(orderId) {
        $scope.params = {};
        $scope.params.order_item_ids =  $scope.orderItemIdsToMarkOutOfStock;
        $scope.params.order_id =  orderId;
        $http({
            method: 'POST',
            url: serverAdminApp + 'product/markOrderItemOutOfStock',
            data : ObjecttoParams($scope.params),
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        }).success(function (response) {
            $scope.ajaxLoadingData = false;
           if(response.status == 'success') {
               location.reload();
           }else{
               alert(response.msg);
           } 
           
        });        
    }
  
});	
