import MascotaHistorial from '@/components/veterinaria/mascotas/MascotaHistorial';

export default async function HistorialClinicoPage({ params }: { params: Promise<{ id: string }> }) {
  const { id } = await params;
  
  return (
    <div className="space-y-8 animate-in fade-in duration-500">
      <MascotaHistorial id={id} />
    </div>
  );
}
