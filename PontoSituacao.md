# FunShirt — Estado do Projeto & Handoff

> Loja online de t-shirts estampadas — projeto de Aplicações para a Internet (EI, 2.º ano).
> Documento de apoio à equipa. Última atualização: **29/05/2026**. Entrega: **13/06/2026**.

---

# PARTE 1 — PONTO DE SITUAÇÃO

## Visão geral

| G | Grupo de funcionalidades | Peso | Estado |
|---|--------------------------|------|--------|
| G1 | Autenticação, Perfil e Gestão de Utilizadores | 20% | ✅ **100%** |
| G2 | Catálogo | 20% | ✅ **100%** |
| G3 | Carrinho de compras | 20% | ✅ **100%** |
| G4 | Encomendas | 20% | ✅ **100%** |
| G5 | Imagens personalizadas | 5% | ✅ **100%** |
| G6 | Recibos e email | 5% | ✅ **100%** |
| G7 | Preview de t-shirts (opcional) | 5% | ✅ **100%** |
| G8 | Estatísticas | 5% | ✅ **100%** |

**Projeto global: 100%.** Os 8 grupos estão fechados.

## Detalhe por grupo

### ✅ G1 — Autenticação, Perfil e Gestão de Utilizadores (FEITO)
- Login/logout, recuperação de password, verificação de email no registo.
- Registo de cliente com nome, email, género e password.
- Perfil do cliente/admin: nome, email, género, foto; secção de faturação (NIF, morada, pagamento) só para clientes.
- Funcionários só alteram a password (sem acesso ao perfil).
- Painel admin: CRUD de funcionários/admins, listar/filtrar/remover clientes, bloquear/desbloquear contas.
- Soft delete condicional: cliente com encomendas/imagens fica em soft delete, sem histórico é apagado fisicamente.
- `UserPolicy`, login bloqueado para contas `blocked`. Admin nunca acede ao perfil privado dos clientes (não existe rota show/edit).

### ✅ G2 — Catálogo (FEITO)
- Catálogo público com pesquisa (nome + descrição) e filtro por categoria, paginado.
- CRUD admin de **categorias** (com upload opcional de imagem).
- CRUD admin de **cores** (com upload obrigatório da t-shirt base — ficheiro guardado como `tshirt_base/{code}.jpg`).
- CRUD admin de **t-shirts** (com upload obrigatório da imagem).
- Configuração de **preços** (linha única — edit/update).
- Todos os formulários usam Form Requests; todas as rotas admin estão sob middleware `auth+admin`.

### ✅ G3 — Carrinho de Compras (FEITO)
- Acessível a anónimos, mantido na sessão, persiste através do login.
- Adicionar do catálogo: escolha de cor, tamanho e quantidade.
- Adicionar t-shirt personalizada (com cor real da BD e tamanho do enum).
- Alteração individual de cor/tamanho/quantidade por linha.
- Quantidade = 0 remove automaticamente o item (regra do enunciado).
- Botão "Limpar carrinho" e "Ir para pagamento".
- Mostra preço unitário, subtotal, total global e badge "Desconto de quantidade aplicado" quando aplicável.
- Cores e tamanhos validados contra a BD (`color_code exists:colors,code`, `size in:XS,S,M,L,XL`).

### ✅ G6 — Recibos e Email (FEITO)
- PDF do recibo gerado ao fechar a encomenda + guardado em `storage/app/private/pdf_receipts/`.
- Emails de encomenda pendente / fechada (com recibo anexo) / anulada, via Mailtrap.
- Download do recibo com acesso restrito (só cliente dono + admin) — `OrderPolicy`, `ReceiptController`.

### ✅ G7 — Preview de T-shirts (FEITO)
- Componente Blade reutilizável `<x-tshirt-preview color-code=… image=… size=sm|md|lg />`.
- Sobreposição CSS puro (sem JS, sem bibliotecas externas) — design por cima da t-shirt base da cor selecionada.
- Integrado no carrinho e no detalhe das encomendas.

### ✅ G8 — Estatísticas (FEITO)
- Painel `/admin/painel` com faturação total/média/mês/ano, t-shirts vendidas, encomendas por estado, gráfico de 12 meses (CSS), top 5 imagens/categorias/clientes, últimas encomendas.

### ✅ G4 — Encomendas (FEITO)
- Checkout exclusivo de clientes autenticados (`auth` + `CheckoutRequest` exige `isCustomer`); anónimos são reencaminhados para login mantendo o carrinho; admin/funcionários não têm acesso.
- **Integração com a plataforma de pagamentos** (secção 7) via Laravel HTTP Client — só regista a encomenda quando a resposta é `201`; pagamento recusado volta ao checkout com mensagem de erro e sem criar encomenda.
- Form pré-preenchido com o perfil do cliente (NIF, morada, tipo e referência de pagamento), editável; campo `notes`; encomenda + itens numa transação de BD.
- Transições pending→closed/canceled (com bloqueio de re-processamento); `reason_for_cancellation` registado na anulação (admin); geração de PDF + emails.
- Histórico do cliente: "As minhas encomendas" (lista + detalhe + download do recibo nas fechadas).
- Gestão (staff/admin): lista mostra o nome do cliente e filtra por estado, **cliente** e data.

### ✅ G5 — Imagens personalizadas (FEITO)
- Área exclusiva do cliente "As minhas imagens" — CRUD completo (consultar, adicionar, editar, remover), tudo sob `auth`.
- Ficheiros guardados em pasta **privada** (`storage/app/private/tshirt_images_private/`), servidos por rota protegida (`TshirtImagePolicy`: só o dono + funcionários/admin). Em circunstância alguma um terceiro acede à imagem (testado: 403).
- Adicionar ao carrinho reutiliza a lógica do catálogo; o carrinho impede adicionar imagens de outro cliente.
- Remoção é soft delete (mantém o histórico das encomendas).

## Requisitos transversais (contam para a nota de TODOS os grupos)

| Aspeto | Estado |
|---|---|
| MVC, Eloquent (nunca o facade `DB`) | ✅ |
| Form Requests para validação | ✅ 11 classes |
| Policies para autorização | ✅ `UserPolicy`, `OrderPolicy` |
| Auth + Hash nativos | ✅ Cast `password=>hashed` no User Model |
| DRY (partials, componentes Blade) | ✅ tabs partial, _form partials, x-tshirt-preview, componentes Breeze |
| Performance (paginação, eager loading) | ✅ todos os índices |
| Segurança | ✅ Todas as rotas protegidas: admin/staff com middleware; checkout, imagens próprias e encomendas do cliente sob `auth`; imagens privadas por Policy. Carrinho público (anónimo OK). |

## Entregáveis (não-código, secção 8 do enunciado)
- ZIP do código (sem `vendor`, `node_modules`, `database`, `storage`, `.git`).
- Relatório do grupo (Excel) — 1 pessoa submete.
- Relatórios individuais (Excel) — cada um submete o seu.
- Link externo com o ZIP completo (no relatório do grupo, ativo ≥ 1 mês).

## O que falta (para entrega)
Os 8 grupos de funcionalidades estão a 100%. Resta apenas a componente **não-código**:
1. Relatório do grupo (Excel) + relatórios individuais (Excel).
2. ZIP do código (excluir `vendor`, `node_modules`, `database`, `storage`, `.git`) + link externo no relatório.
3. **Importante ao clonar/entregar:** correr `composer install` (o `barryvdh/laravel-dompdf` não vinha instalado no `vendor/` e os recibos PDF falhavam sem ele) e `php artisan migrate --seed` + `storage:link`.

Sugestões opcionais de polish (não obrigatórias): incluir a imagem no PDF do recibo; enviar emails via Queue (`ShouldQueue`) para resposta mais rápida.

## Como correr o projeto localmente
- Ambiente: Laragon (Windows), PHP 8.4+, Node 24.
- Terminal 1: `php artisan serve` · Terminal 2: `npm run dev`
- Aceder em **http://localhost:8000**.
- Base de dados SQLite. Todos os utilizadores de teste têm password `123`.
  Admins: `a1/a2/a3@mail.pt` (**a1 está BLOQUEADO no seed — usar a2 ou a3**).
  Funcionários: `f1/f2/f3@mail.pt`.
  Clientes: `c1..c10@mail.pt`.

---

# PARTE 2 — PROMPT PARA DAR À IA

> Copiar tudo o que está dentro do bloco abaixo e colar no início de uma sessão
> com a IA (Gemini/Claude/etc.) antes de pedir trabalho em G4/G5.

```
Estás a ajudar um grupo de 3 alunos num projeto de faculdade: a loja online
"FunShirt" (t-shirts estampadas), em Laravel. Lê todo este contexto antes de
escrever código.

== REGRAS DE TRABALHO (obrigatórias) ==
- NÃO usar JavaScript para lógica. Permitido só: Livewire e JS residual para
  pequenos efeitos de UI (o Breeze já traz Alpine.js — não acrescentar JS novo).
- NÃO alterar a base de dados. A migração 2026_03_26_155238_initial.php é
  fornecida pelo docente e é fixa.
- Trabalhar de forma simples, sem inventar demasiado (é projeto académico,
  tem de ser explicável a um professor).
- Boas práticas Laravel: MVC, Eloquent (nunca o facade DB), Form Requests
  para validação, Policies para autorização, Hash/auth nativos, rotas e
  métodos HTTP corretos, DRY (partials, componentes Blade), performance
  (paginar, select() só do necessário, eager loading).
- Interface em Português. UI em Tailwind, reutilizando os componentes Breeze
  (x-text-input, x-input-label, x-input-error, x-primary-button, x-app-layout).
- Toda a lógica vive nos controllers, nada em routes/web.php nem nas views.

== STACK ==
- Laravel 13 + SQLite + Breeze (Blade + Alpine). PHP 8.4 (Laragon), Node 24.
- Correr: php artisan serve + npm run dev -> http://localhost:8000.
- Pacote barryvdh/laravel-dompdf instalado (PDFs).
- Todos os utilizadores de teste têm password "123".
  a1 está BLOQUEADO — usar a2/a3 para admin; f1..f3 funcionários; c1..c10 clientes.

== FACTOS TÉCNICOS (todas as IAs têm de saber) ==
- user_type: C (Cliente), F (Funcionário), A (Administrador). NÃO existe "E".
- gender: M ou F, NOT NULL.
- customers é subclasse de users: mesma PK (não autoincremental). Criar User
  primeiro, depois Customer com o id gerado; remover pela ordem inversa.
- User e Customer usam o trait SoftDeletes.
- Relações: $user->customer e $customer->user.
- Helpers no User: isAdmin(), isEmployee(), isCustomer(), photoLink().
- Password do User tem cast 'hashed' — NÃO chamar Hash::make() (é redundante).
- O Controller base usa AuthorizesRequests ($this->authorize(...) funciona).
- Middleware aliases: 'admin' -> App\Http\Middleware\IsAdmin,
                      'staff' -> App\Http\Middleware\IsStaff (F+A).
- Policies existentes (auto-discovered): UserPolicy, OrderPolicy.
- A navbar está em resources/views/layouts/app.blade.php (role-aware, Tailwind).
  layouts/navigation.blade.php NÃO é usado.
- Flash: a área de admin usa session('success'); o perfil usa session('status')
  com códigos. Não misturar.
- Storage: fotos em storage/app/public/photos (campo photo_url guarda só o nome
  do ficheiro). Recibos em storage/app/private/pdf_receipts (disco 'local').
  Imagens t-shirts em storage/app/public/tshirt_images.
  T-shirts base por cor em storage/app/public/tshirt_base/{code}.jpg.
  Categorias em storage/app/public/categories.
- Email verification está ativo (User implements MustVerifyEmail); staff criado
  pelo admin é pré-verificado.
- Componente preview: <x-tshirt-preview color-code="..." image="..." size="md" />
  (overlay CSS puro, integrado em cart e orders/show).
- Pagamentos simulados: API em https://ainet-payments-api.vercel.app
  (POST /api/payments), consumir com o Laravel HTTP Client.
- Emails via mailtrap.io.

== ESTADO DOS GRUPOS ==
FEITOS (100%): G1 (auth/perfil/utilizadores), G2 (catálogo), G3 (carrinho),
G6 (recibos/email), G7 (preview), G8 (estatísticas).

POR FAZER:
- G4 Encomendas (~55%): falta a integração com a plataforma de pagamentos
  (secção 7 do enunciado). BUG: CheckoutController tem $customerId=22 fixo.
  Checkout não é exclusivo de clientes; faltam notes, reason_for_cancellation,
  pré-preenchimento, histórico do cliente. Rotas /checkout sem middleware.
- G5 Imagens personalizadas (~40%): imagens estão em pasta pública (deviam
  ser privadas em tshirt_images_private); falta a área de gestão das imagens
  do cliente; /personalizar ainda é público (devia ser só cliente).

TRANSVERSAL: rotas /checkout e /personalizar ainda sem middleware adequado.
O carrinho TEM de continuar a funcionar para anónimos.

== COMO TRABALHAR ==
- Antes de mexer em código de outro grupo de funcionalidades, avisar o colega
  responsável.
- Verificar sempre: php artisan route:list, php artisan view:cache (apanha
  erros de Blade) e testar no browser autenticado com a2@mail.pt (admin).
- ZIP de entrega exclui vendor, node_modules, database, storage e .git.
```
