import AtencionSelection from '@/components/veterinaria/atenciones/AtencionSelection';
import { Suspense } from 'react';
import { Loader2 } from 'lucide-react';

export default function NuevaAtencionPage() {
  return (
    <div className="py-8">
      <Suspense fallback={
        <div className="flex items-center justify-center py-20">
          <Loader2 className="animate-spin text-[#2ecc71]" size={48} />
        </div>
      }>
        <AtencionSelection />
      </Suspense>
    </div>
  );
}
