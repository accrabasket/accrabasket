var app = angular.module('app', []);
app.controller('ledger', function ($scope, $http,$timeout,$filter) {
    $scope.errorShow = false;
    $scope.showAttr = false;
    $scope.filter = {};
    $scope.currentPage = 1;
    $scope.numberOfRecord = 0;
    $scope.ajaxLoadingData = false;
    
    var nowTemp = new Date();
    nowTemp.setMonth(nowTemp.getMonth() - 1);
    $scope.filter.startDate =  $filter('date')(nowTemp, 'yyyy-MM-dd');
    nowTemp.setMonth(nowTemp.getMonth() + 1);
    $scope.filter.endDate =  $filter('date')(nowTemp, 'yyyy-MM-dd');
    $scope.intCall = function(){
        $scope.fetchMerchant();
    }
    
    $scope.fetchLedgerData = function() {
        $scope.ajaxLoadingData = true;
        $http({
            method: 'POST',
            url: serverAdminApp + 'dashboard/getledger',
            data : ObjecttoParams($scope.filter),
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        }).success(function (response) {
            $scope.ajaxLoadingData = false;
            $scope.ledgerList = {};
            $scope.ledgerSummery = {};
            if(response.status == 'success'){
                $scope.ledgerSummery = response.data['total_summery'];
                delete response.data['total_summery'];
                $scope.ledgerList = response.data;
            }
        });        
    };
    
    $scope.applyFilter = function(){
        var error ='';
        if($scope.filter.merchant_id == undefined || $scope.filter.merchant_id == ''){
            error = 'Please select merchant';
        }
        
        if(error == ''){
            $scope.filter.endDate = ($scope.filter.endDate);
            $scope.filter.startDate = ($scope.filter.startDate);
            $scope.fetchLedgerData(); 
        }else{
            $scope.errorShow = true;
            $scope.errorMsg = error;
            $timeout(function () {
                $scope.errorShow = false;
            }, 2000)
        }
        
    }
    
    $scope.fetchMerchant = function() {
        $scope.ajaxLoadingData = true;
        $http({
            method: 'POST',
            url: serverAdminApp + 'product/merchantList',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        }).success(function (response) {
            $scope.ajaxLoadingData = false;
            $scope.merchantList = {};
            if(response.status == 'success'){
                $scope.merchantList = response.data;
            }
        });        
    };
    
});