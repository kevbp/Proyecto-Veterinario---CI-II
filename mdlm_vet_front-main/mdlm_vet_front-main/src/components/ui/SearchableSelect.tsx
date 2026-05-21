'use client';

import { useState, useRef, useEffect } from 'react';
import { Search, ChevronDown, X } from 'lucide-react';

interface Option {
  id: string | number;
  label: string;
  sublabel?: string;
}

interface SearchableSelectProps {
  options: Option[];
  value: string | number;
  onChange: (value: string | number) => void;
  placeholder?: string;
  label?: string;
  error?: string;
  disabled?: boolean;
  required?: boolean;
  className?: string;
}

export default function SearchableSelect({
  options,
  value,
  onChange,
  placeholder = 'Seleccione una opción',
  label,
  error,
  disabled = false,
  required = false,
  className = '',
}: SearchableSelectProps) {
  const [isOpen, setIsOpen] = useState(false);
  const [searchTerm, setSearchTerm] = useState('');
  const wrapperRef = useRef<HTMLDivElement>(null);

  const selectedOption = options.find(opt => opt.id.toString() === value?.toString());

  useEffect(() => {
    function handleClickOutside(event: MouseEvent) {
      if (wrapperRef.current && !wrapperRef.current.contains(event.target as Node)) {
        setIsOpen(false);
      }
    }
    document.addEventListener('mousedown', handleClickOutside);
    return () => document.removeEventListener('mousedown', handleClickOutside);
  }, []);

  const filteredOptions = options.filter(opt => 
    opt.label.toLowerCase().includes(searchTerm.toLowerCase()) || 
    opt.sublabel?.toLowerCase().includes(searchTerm.toLowerCase())
  );

  return (
    <div className={`relative ${className}`} ref={wrapperRef}>
      {label && (
        <label className="block text-[13px] font-bold text-gray-700 mb-2">
          {label} {required && <span className="text-pink-500">*</span>}
        </label>
      )}
      
      <div 
        onClick={() => !disabled && setIsOpen(!isOpen)}
        className={`
          flex items-center justify-between w-full px-4 py-2.5 bg-white border rounded-xl cursor-pointer transition-all
          ${isOpen ? 'border-[#2ecc71] ring-2 ring-[#2ecc71]/20' : 'border-gray-200'}
          ${disabled ? 'bg-gray-50 cursor-not-allowed opacity-60' : 'hover:border-gray-300'}
          ${error ? 'border-red-500 ring-red-500/20' : ''}
        `}
      >
        <span className={`text-sm truncate ${!selectedOption ? 'text-gray-400' : 'text-gray-700 font-medium'}`}>
          {selectedOption ? selectedOption.label : placeholder}
        </span>
        <ChevronDown 
          size={18} 
          className={`text-gray-400 transition-transform duration-200 ${isOpen ? 'rotate-180' : ''}`} 
        />
      </div>

      {isOpen && (
        <div className="absolute z-[60] w-full mt-2 bg-white border border-gray-100 rounded-2xl shadow-xl overflow-hidden animate-in fade-in zoom-in-95 duration-200">
          <div className="p-2 border-b border-gray-50 bg-gray-50/50">
            <div className="relative">
              <Search className="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" size={16} />
              <input 
                autoFocus
                type="text"
                placeholder="Buscar..."
                value={searchTerm}
                onChange={(e) => setSearchTerm(e.target.value)}
                className="w-full pl-9 pr-4 py-2 bg-white border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#2ecc71]/30"
                onClick={(e) => e.stopPropagation()}
              />
            </div>
          </div>
          
          <div className="max-h-[200px] overflow-y-auto overflow-x-hidden scrollbar-thin scrollbar-thumb-gray-200">
            {filteredOptions.length === 0 ? (
              <div className="px-4 py-8 text-center text-gray-400 text-sm">
                No se encontraron resultados
              </div>
            ) : (
              filteredOptions.map((opt) => (
                <div
                  key={opt.id}
                  onMouseDown={(e) => {
                    // Usamos onMouseDown para que se ejecute antes del blur
                    e.preventDefault();
                    e.stopPropagation();
                    onChange(opt.id);
                    setIsOpen(false);
                    setSearchTerm('');
                  }}
                  className={`
                    px-4 py-2.5 cursor-pointer transition-colors hover:bg-[#2ecc71]/10
                    ${opt.id.toString() === value?.toString() ? 'bg-[#2ecc71]/5 text-[#015f33] font-bold' : 'text-gray-600'}
                  `}
                >
                  <div className="text-sm">{opt.label}</div>
                  {opt.sublabel && <div className="text-[11px] opacity-60 mt-0.5">{opt.sublabel}</div>}
                </div>
              ))
            )}
          </div>
        </div>
      )}
      
      {error && <p className="mt-1.5 text-[11px] text-red-500 font-medium pl-1">{error}</p>}
    </div>
  );
}
