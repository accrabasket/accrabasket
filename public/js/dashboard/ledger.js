var app = angular.module('app', []);
app.controller('ledger', function ($scope, $http,$timeout,$filter) {
    $scope.errorShow = false;
    $scope.showAttr = false;
    $scope.filter = {};
    $scope.currentPage = 1;
    $scope.numberOfRecord = 0;
    $scope.ajaxLoadingData = false;
    $scope.paytomerchant = {};
    $scope.showLedger = false;
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
            $scope.showLedger = true;
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
    
    $scope.payToMerchant = function(){
        var error ='';
        
        if($scope.paytomerchant.amount == undefined || $scope.paytomerchant.amount == ''){
            error = 'Please enter  amount';
        }
        if($scope.filter.merchant_id == undefined || $scope.filter.merchant_id == ''){
            error = 'Please select merchant';
        }
        
        $scope.paytomerchant.merchant_id = $scope.filter.merchant_id;
        if(error == ''){
            $scope.ajaxLoadingData = true;
            $http({
                method: 'POST',
                url: serverAdminApp + 'dashboard/paytomerchant',
                data : ObjecttoParams($scope.paytomerchant),
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            }).success(function (response) {
                $scope.ajaxLoadingData = false;
                if(response.status == 'success'){
                    $scope.fetchLedgerData();
                    $scope.successShow = true;
                    $scope.successMsg = response.msg;
                }else{
                    $scope.errorShow = true;
                    $scope.errorMsg = response.msg;
                }
                $timeout(function () {
                    $scope.errorShow = false;
                    $scope.successShow = false;
                }, 2000)
            });  
        }else{
            $scope.errorShow = true;
            $scope.errorMsg = error;
            $timeout(function () {
                $scope.errorShow = false;
            }, 2000)
        }
    }
    
});