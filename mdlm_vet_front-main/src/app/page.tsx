'use client';

import { useState } from 'react';
import Image from 'next/image';
import Link from 'next/link';
import Footer from '@/components/common/Footer';
import Button from '@/components/common/Button';
import CampaignList from '@/components/veterinaria/CampaignList';
import ServiceCatalog from '@/components/veterinaria/ServiceCatalog';
import api from '@/utils/api';
import ssoApi from '@/utils/api';
import { Campaign, CampaignResponse } from '@/interfaces/Campaign';

type RightPanel = 'dog' | 'campaigns' | 'services';

export default function Home() {
  const [activePanel, setActivePanel] = useState<RightPanel>('dog');
  const [campaigns, setCampaigns] = useState<Campaign[]>([]);
  const [isLoading, setIsLoading] = useState(false);
  const [error, setError] = useState<string | null>(null);

  const handleToggleCampaigns = async () => {
    if (activePanel === 'campaigns') {
      setActivePanel('dog');
      return;
    }

    setActivePanel('campaigns');
    setIsLoading(true);
    setError(null);

    try {
      const { data } = await api.get<CampaignResponse>('/public/campanias-activas');
      setCampaigns(data.data);
    } catch (err: any) {
      setError(err.response?.data?.message || 'Error al cargar campañas');
    } finally {
      setIsLoading(false);
    }
  };

  const handleToggleServices = () => {
    setActivePanel(activePanel === 'services' ? 'dog' : 'services');
  };

  const handleClosePanel = () => {
    setActivePanel('dog');
  };

  return (
    <main className="flex min-h-screen flex-col text-white relative overflow-hidden"
      style={{
        background: 'linear-gradient(135deg, #013d21 0%, #015f33 30%, #017a42 60%, #015f33 100%)',
      }}
    >
      {/* Decorative background circles */}
      <div className="absolute inset-0 pointer-events-none overflow-hidden">
        <div className="absolute -top-32 -right-32 w-[600px] h-[600px] rounded-full border border-white/5" />
        <div className="absolute -top-16 -right-16 w-[500px] h-[500px] rounded-full border border-white/5" />
        <div className="absolute top-1/2 -left-48 w-[400px] h-[400px] rounded-full border border-white/5" />
        <div className="absolute bottom-0 right-1/3 w-[350px] h-[350px] rounded-full border border-white/5" />
        <div className="absolute top-20 left-1/4 w-64 h-64 bg-[#2ecc71]/5 rounded-full blur-3xl" />
        <div className="absolute bottom-32 right-1/4 w-80 h-80 bg-[#fec107]/5 rounded-full blur-3xl" />
      </div>

      {/* Hero section */}
      <div className="flex-grow grid grid-cols-1 md:grid-cols-2 gap-8 items-center px-8 py-12 md:px-20 md:py-16 max-w-7xl mx-auto w-full relative z-10">

        {/* Lado Izquierdo */}
        <div className="space-y-8">
          {/* Logo de la Municipalidad */}
          <div className="flex items-center gap-4">
            <Image
              src="/logo_munimolina.png"
              alt="Municipalidad de La Molina"
              width={260}
              height={90}
              priority
              className="h-16 md:h-20 w-auto drop-shadow-lg"
            />
          </div>

          {/* Título */}
          <h1 className="text-4xl md:text-5xl lg:text-6xl font-extrabold leading-tight">
            <span className="text-white block">Veterinaria</span>
            <span className="text-[#fec107] block">Municipal</span>
          </h1>

          {/* Botones */}
          <div className="space-y-4 flex flex-col max-w-sm">
            <Button
              variant="primary"
              fullWidth
              onClick={() => {
                const ssoUrl = process.env.NEXT_PUBLIC_SSO_URL || 'http://sso.test/login';
                const callback = encodeURIComponent(window.location.origin + '/auth/callback');
                window.location.href = `${ssoUrl}?callback=${callback}`;
              }}
            >
              Ingresar
            </Button>

            <Button variant="outline" fullWidth onClick={handleToggleServices}>
              Ver catalogo de servicios
            </Button>

            <Button variant="outline" fullWidth onClick={handleToggleCampaigns}>
              Cronograma de campañas
            </Button>
          </div>

          {/* Enlaces Legales */}
          {/* <div className="text-sm text-white/60 space-x-6">
            <a href="#" className="underline hover:text-white transition-colors duration-200">Políticas de Privacidad</a>
            <a href="#" className="underline hover:text-white transition-colors duration-200">Políticas de cookies</a>
          </div> */}
        </div>

        {/* Lado Derecho: Dog SVG, CampaignList o ServiceCatalog */}
        <div className="flex justify-center md:justify-end items-start md:items-center">
          {activePanel === 'campaigns' && (
            <CampaignList
              campaigns={campaigns}
              isLoading={isLoading}
              error={error}
              onClose={handleClosePanel}
            />
          )}

          {activePanel === 'services' && (
            <ServiceCatalog onClose={handleClosePanel} />
          )}

          {activePanel === 'dog' && (
            <div className="relative">
              <div className="absolute inset-0 bg-[#2ecc71]/15 blur-3xl rounded-full scale-90" />
              <Image
                src="/dog.svg"
                alt="Ilustración de mascota - Veterinaria Municipal"
                width={420}
                height={420}
                priority
                className="relative z-10 w-[280px] md:w-[380px] lg:w-[420px] h-auto 
                           fill-white drop-shadow-2xl
                           hover:scale-105 transition-transform duration-500 ease-out"
                style={{ filter: 'brightness(0) invert(1) opacity(0.85)' }}
              />
            </div>
          )}
        </div>
      </div>

      {/* Footer */}
      <Footer />
    </main>
  );
}