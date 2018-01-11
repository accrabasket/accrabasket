var app = angular.module('product', ['ui.bootstrap']);
app.controller('productController', function ($scope, $http,count,productList) {
    $scope.errorShow = false;
    $scope.showAttr = false;
    $scope.productData = {};
    $scope.filter = {};
    $scope.index = 0;
    $scope.ajaxLoadingData = false;

    $scope.indexVal = [];
    $scope.errorStatus = false;
    $scope.errorMsg = '';
    $scope.selected_filter_level = 'Action';
    $scope.productList = productList;
    $scope.setFilterType = function(id){
        $scope.filter.filter_type = id;
        $scope.selected_filter_level = id.replace("_", " ");
    }
    
    $scope.selectPage = function(page_number) {
        $scope.filter.page = page_number;
        $scope.getProductList();
    };
    
    $scope.currentPage = 1;
    $scope.numberOfRecord = count;
    
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
            $scope.getProductList();
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
        $scope.getProductList();
    }
    
    $scope.getProductList = function() { 
        $scope.ajaxLoadingData = true;
        $http({
            method: 'POST',
            url: serverAdminApp + 'product/getProductList',
            data : ObjecttoParams($scope.filter),
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        }).success(function (response) {
            $scope.productList = {};
            $scope.ajaxLoadingData = false;
            if(response.status == 'success'){
                $scope.productList = response.data;
                $scope.numberOfRecord = response.totalRecord;
            }else{
                $scope.numberOfRecord = 0;
            }
        });
    }    
    
    
    
	
});	


function checkform(){
    var ret  = true;
    var msg = '';
    if($('#product_name').val() == ''){
        msg = 'Product name should not blank';
        ret = false;
    }
    if($('#category_id').val() == ''){
        msg = 'Product category id should not blank';
        ret = false;
    }
    if($('#product_name').val() == ''){
        msg = 'Product name should not blank';
        ret = false;
    }
    
    var index = $('#index').val();
    
   for(var i=0; i<index ; i++){
       var newindex  = i+1;
       if($('#attribute_name_'+newindex).val() == ''){
            msg = 'attribute name should not blank';
            ret = false;
        }
        if($('#attribute_quantity_'+newindex).val() == ''){
            msg = 'quantity should not blank';
            ret = false;
        }
        if($('#attribute_unit_'+newindex).val() == ''){
            msg = 'Unit should not blank';
            ret = false;
        }
        if($('#attribute_commission_type_'+newindex).val() == 'percent' && $('#attribute_commission_value_'+newindex).val() > 70){
            msg = 'Commission percent should be less then 70 %';
            ret = false;
        }
   }
   if(!ret){
       alert(msg);
   }
   return ret;
}


var itemsDetails = [
    { itemCode : 100,
      itemName : 'ONE',
      itemDescription : 'Hey Hie',
      uom : 'FEET',
      available : 'YES',
      openStock : 7,
      restrictStock : 16,
      threshold : 0,
      active : 'YES'
      },
    { itemCode : 102,
      itemName : 'TWO',
      itemDescription : 'Hey Hie',
      uom : 'GALLONS',
      available : 'YES',
      openStock : 8,
      restrictStock : 15,
      threshold : 0,
      active : 'YES'
      },
    { itemCode : 103,
      itemName : 'THREE',
      itemDescription : 'Hey Hie',
      uom : 'GALLONS',
      available : 'YES',
      openStock : 0,
      restrictStock : 15,
      threshold : 0,
      active : 'YES'
      },
    { itemCode : 104,
      itemName : 'FOUR',
      itemDescription : 'Hey Hie',
      uom : 'FEET',
      available : 'YES',
      openStock : 0,
      restrictStock : 15,
      threshold : 0,
      active : 'YES'
      },
    { itemCode : 105,
      itemName : 'FIVE',
      itemDescription : 'Hey Hie',
      uom : 'FEET',
      available : 'YES',
      openStock : 0,
      restrictStock : 14,
      threshold : 0,
      active : 'YES'
      },
    { itemCode : 106,
      itemName : 'SIX',
      itemDescription : 'Hey Hie',
      uom : 'FEET',
      available : 'YES',
      openStock : 0,
      restrictStock : 5,
      threshold : 0,
      active : 'YES'
      },
    { itemCode : 107,
      itemName : 'SEVEN',
      itemDescription : 'Hey Hie',
      uom : 'GALLONS',
      available : 'YES',
      openStock : 0,
      restrictStock : 5,
      threshold : 0,
      active : 'YES'
      },
    { itemCode : 108,
      itemName : 'EIGHT',
      itemDescription : 'Hey Hie',
      uom : 'FEET',
      available : 'YES',
      openStock : 5,
      restrictStock : 0,
      threshold : 0,
      active : 'YES'
      },
    { itemCode : 109,
      itemName : 'NINE',
      itemDescription : 'Hey Hie',
      uom : 'GALLONS',
      available : 'YES',
      openStock : 5,
      restrictStock : 0,
      threshold : 0,
      active : 'YES'
      },
    { itemCode : 110,
      itemName : 'TEN',
      itemDescription : 'Hey Hie',
      uom : 'FEET',
      available : 'YES',
      openStock : 77,
      restrictStock : 0,
      threshold : 0,
      active : 'YES'
      }
  ];

