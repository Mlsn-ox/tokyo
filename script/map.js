export function mapping(lat, lng) {
  const map = L.map("map").setView([lat, lng], 12);
  let layerGroup = L.layerGroup();

  const street = L.tileLayer(
    "https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png",
    {
      attribution:
        '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
      maxZoom: 19,
    }
  ).addTo(map);

  const sat = L.tileLayer(
    "https://api.mapbox.com/styles/v1/mapbox/satellite-streets-v12/tiles/{z}/{x}/{y}?access_token=pk.eyJ1IjoibWxzbiIsImEiOiJjbTZpMjR6dzMwMnRmMmlxc2NsdGNpdzBtIn0.ODgHxRTgvTEgXe9jH7r88A",
    {
      attribution: '&copy; <a href="https://www.mapbox.com/">Mapbox</a>',
      maxZoom: 19,
    }
  );
  const baseMaps = {
    OpenStreet: street,
    Satellite: sat,
  };
  L.control.layers(baseMaps).addTo(map);
  L.control.scale().addTo(map);
  return map;
}
