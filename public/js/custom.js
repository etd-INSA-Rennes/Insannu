angular.module('insannu', ['ngResource'])
  .controller('SearchController', ['$scope', '$resource', function($scope, $resource) {
    var StudentsFactory = $resource('/api.php/search/:search');

    $scope.$watch('search', function() {
      $scope.students = StudentsFactory.query({search:$scope.search}, function() {
        console.log('Found');
      });
    });

    $scope.setSearch = function(val) {
      $scope.search = val;
    };
  }])
;
