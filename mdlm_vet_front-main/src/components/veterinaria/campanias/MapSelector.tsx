'use client';

import { MapContainer, TileLayer, Marker, useMapEvents, useMap } from 'react-leaflet';
import 'leaflet/dist/leaflet.css';
import L from 'leaflet';
import { useState, useEffect } from 'react';

// Fix Leaflet icon issue in Next.js
if (typeof window !== 'undefined') {
  delete (L.Icon.Default.prototype as any)._getIconUrl;
  L.Icon.Default.mergeOptions({
    iconRetinaUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/images/marker-icon-2x.png',
    iconUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/images/marker-icon.png',
    shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/images/marker-shadow.png',
  });
}

// Add this component to fix the "broken tiles" issue
function FixMapResize() {
  const map = useMap();
  useEffect(() => {
    // This forces the map to recalculate its dimensions after initial load
    setTimeout(() => {
      map.invalidateSize();
    }, 250);
  }, [map]);
  return null;
}

interface MapSelectorProps {
  onLocationSelect: (address: string, lat: number, lng: number) => void;
  initialAddress?: string;
  initialLat?: number;
  initialLng?: number;
}

export default function MapSelector({ onLocationSelect, initialAddress, initialLat, initialLng }: MapSelectorProps) {
  const [position, setPosition] = useState<[number, number] | null>(
    initialLat && initialLng ? [initialLat, initialLng] : null
  );
  const [address, setAddress] = useState(initialAddress || '');
  const [isSearching, setIsSearching] = useState(false);

  // Center of La Molina by default (approx)
  const defaultCenter: [number, number] = [-12.0772, -76.9427];

  const fetchAddressFromCoords = async (lat: number, lng: number) => {
    try {
      const response = await fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`);
      const data = await response.json();
      if (data && data.display_name) {
        setAddress(data.display_name);
        onLocationSelect(data.display_name, lat, lng);
      }
    } catch (error) {
      console.error('Error fetching address:', error);
    }
  };

  const handleSearch = async () => {
    if (!address.trim()) return;
    setIsSearching(true);
    try {
      const response = await fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(address)}`);
      const data = await response.json();
      if (data && data.length > 0) {
        const { lat, lon } = data[0];
        const newPos: [number, number] = [parseFloat(lat), parseFloat(lon)];
        setPosition(newPos);
        setAddress(data[0].display_name);
        onLocationSelect(data[0].display_name, newPos[0], newPos[1]);
      }
    } catch (error) {
      console.error('Error searching address:', error);
    } finally {
      setIsSearching(false);
    }
  };

  function MapEvents() {
    useMapEvents({
      click(e) {
        setPosition([e.latlng.lat, e.latlng.lng]);
        fetchAddressFromCoords(e.latlng.lat, e.latlng.lng);
      },
    });
    return null;
  }

  // Effect to sync external changes if needed
  useEffect(() => {
    if (initialAddress && initialAddress !== address && !position) {
      setAddress(initialAddress);
    }
    if (initialLat && initialLng && (!position || (position[0] !== initialLat || position[1] !== initialLng))) {
      setPosition([initialLat, initialLng]);
    }
  }, [initialAddress, initialLat, initialLng]);

  return (
    <div className="space-y-3">
      <div className="flex gap-2 relative">
        <div className="relative flex-1">
          <input
            type="text"
            value={address}
            onChange={(e) => {
              setAddress(e.target.value);
              onLocationSelect(e.target.value, position?.[0] || 0, position?.[1] || 0);
            }}
            onKeyDown={(e) => e.key === 'Enter' && (e.preventDefault(), handleSearch())}
            placeholder="Buscar dirección o haz clic en el mapa..."
            className="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-[#11ba82] focus:border-[#11ba82] text-sm outline-none"
          />
          <div className="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><circle cx="11" cy="11" r="8"></circle><path d="m21 21-4.3-4.3"></path></svg>
          </div>
        </div>
        <button
          type="button"
          onClick={handleSearch}
          disabled={isSearching}
          className="px-4 py-2 bg-white border border-gray-200 text-[#11ba82] font-semibold rounded-xl hover:bg-gray-50 disabled:opacity-50 transition-colors"
        >
          {isSearching ? 'Buscando...' : 'Buscar'}
        </button>
      </div>
      
      <div className="h-[300px] w-full rounded-2xl overflow-hidden border border-gray-200 shadow-sm relative z-0">
        <MapContainer center={position || defaultCenter} zoom={15} style={{ height: '100%', width: '100%' }}>
          <TileLayer
            attribution='&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            url="https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png"
          />
          <FixMapResize />
          <MapEvents />
          {position && <Marker position={position} />}
        </MapContainer>
      </div>
    </div>
  );
}
