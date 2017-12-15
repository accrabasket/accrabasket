app.controller('managetimeslot', function ($scope, $http, $sce,$timeout, timeslotList) {
    $scope.errorShow = false;

    $scope.timeslotData = {};

console.log(timeslotList);
    if(timeslotList != undefined && timeslotList != '') {
        $scope.timeslotData.id = timeslotList.id;
        $scope.timeslotData = timeslotList;
    }    

    $scope.saveTimeslot = function (timeslotData) {
        var error = ' ';
        if (timeslotData.start_time_slot == undefined || timeslotData.start_time_slot == '') {
            error = 'Please enter start time like 12.';
        }
        
        if (timeslotData.end_time_slot == undefined || timeslotData.end_time_slot == '') {
            error = 'Please enter end time like 02.';
        }
        
        if (timeslotData.end_time_slot == timeslotData.start_time_slot ) {
            error = 'Start and end time should be diffrent.';
        }
        
            

        if (error == ' ') {
            $http({
                method: 'POST',
                data: ObjecttoParams(timeslotData),
                url: serverAdminApp + 'dashboard/savetimeslot',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            }).success(function (response) {
                if (response.status == 'success') {
                    $scope.successShow = true;
                    $scope.successMsg = response.msg;
                    $scope.successShow = false;
                    var path = serverAdminApp + 'dashboard/managetimeslot';
                    window.location.href = path;
                } else {
                    $scope.errorShow = true;
                    $scope.errorMsg = response.msg == undefined ? 'somthing went wrong ' : response.msg;
                    $timeout(function () {
                        $scope.errorShow = false;
                    }, 2000);
                }
            });
        } else {
            $scope.errorShow = true;
            $scope.errorMsg = error;
            $timeout(function () {
                $scope.errorShow = false;
            }, 2000)
        }

    };


});	