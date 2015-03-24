function codeLatLng( latlng) {
    $('#form_direccion').val("");
    var street_number;
    var address;
    geocoder.geocode({'latLng': latlng}, function(results, status) {
        if (status == google.maps.GeocoderStatus.OK) {
            if (results[0]) {
                map.setCenter(latlng);
                map.setZoom(15);
                searchAddressComponents = results[0].address_components;
                console.log(results[0].address_components);
                $.each(searchAddressComponents, function(){
                    if(this.types[0]=="street_number"){
                        street_number = this.short_name;
                    }
                    if(this.types[0]=="route"){
                        address = this.short_name;
                    }
                    if(this.types[0]=="locality"){
                        $('#form_municipio').val(this.short_name);
                    }
                    if(this.types[0]=="postal_code"){
                        $('#form_codigo_postal').val(this.short_name);
                    }
                    if(this.types[0]=="administrative_area_level_2"){
                        $('#form_estado').val(this.long_name);
                    }

                });
                if(street_number){
                    $('#form_direccion').val(address + ', ' + street_number);
                }
                else{
                    $('#form_direccion').val(address );
                }

            }
        } else {
            alert("Geocoder failed due to: " + status);
        }
    });
}

function codeAddress() {
    var address = $('#form_direccion').val() + "," + $('#form_municipio').val() + "," + $('#form_codigo_postal').val();
    geocoder.geocode( { 'address': address}, function(results, status) {
        if (status == google.maps.GeocoderStatus.OK) {
            map.setCenter(results[0].geometry.location );
            $('#form_latitud').val(results[0].geometry.location.lat());
            $('#form_longitud').val(results[0].geometry.location.lng());
            map.setZoom(15);
            marker = new google.maps.Marker({
                map: map,
                position: results[0].geometry.location
            });
        } else {
            alert('Geocode was not successful for the following reason: ' + status);
        }
    });
}