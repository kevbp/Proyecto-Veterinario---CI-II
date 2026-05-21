import { Wrench, Construction, ShieldAlert } from 'lucide-react';
import Link from 'next/link';

export default function CajaPage() {
  return (
    <div className="flex-1 flex flex-col items-center justify-center min-h-[70vh] animate-in fade-in duration-500">
      <div className="bg-white/50 backdrop-blur-md rounded-[32px] p-12 shadow-sm border border-white/60 max-w-2xl w-full text-center relative overflow-hidden group">
        
        {/* Decorative elements */}
        <div className="absolute -top-24 -right-24 w-48 h-48 bg-[#fec107]/20 rounded-full blur-3xl group-hover:bg-[#fec107]/30 transition-colors duration-500"></div>
        <div className="absolute -bottom-24 -left-24 w-48 h-48 bg-[#fec107]/20 rounded-full blur-3xl group-hover:bg-[#fec107]/30 transition-colors duration-500"></div>
        
        <div className="relative z-10 flex flex-col items-center">
          <div className="w-24 h-24 bg-gradient-to-br from-[#fec107] to-yellow-400 rounded-3xl flex items-center justify-center text-white shadow-xl shadow-yellow-400/20 mb-8 border-4 border-white transform -rotate-6 group-hover:rotate-0 transition-transform duration-300">
            <Construction size={48} strokeWidth={1.5} />
          </div>
          
          <h2 className="text-3xl font-extrabold text-gray-800 mb-4 tracking-tight">
            Módulo en Desarrollo
          </h2>
          
          <p className="text-gray-500 mb-8 text-lg leading-relaxed max-w-md mx-auto">
            El módulo de <span className="font-bold text-gray-700">Caja y Ventas</span> se encuentra actualmente en fase de construcción. Estamos afinando los criterios de la lógica de negocio para ofrecerte la mejor experiencia.
          </p>

          <div className="bg-orange-50/80 border border-orange-100 rounded-2xl p-5 mb-8 flex items-start gap-4 text-left max-w-lg">
            <div className="mt-0.5">
              <ShieldAlert className="text-orange-500" size={24} />
            </div>
            <div>
              <h4 className="text-orange-800 font-bold text-[15px] mb-1">Aviso Importante</h4>
              <p className="text-orange-700/80 text-[13px] leading-snug">
                Pronto podrás gestionar pagos, emitir comprobantes y administrar el inventario de la veterinaria desde este apartado.
              </p>
            </div>
          </div>

          <Link 
            href="/dashboard" 
            className="bg-white text-gray-600 border border-gray-200 px-8 py-3 rounded-xl font-bold shadow-sm hover:bg-gray-50 hover:text-[#015f33] hover:border-transparent hover:shadow-md transition-all duration-300 transform hover:-translate-y-0.5"
          >
            Volver al Dashboard
          </Link>
        </div>
      </div>
    </div>
  );
}
